<?php

namespace App\Livewire\Admin;

use App\Models\PlexSettings as PlexSettingsModel;
use App\Services\PlexService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PlexSettings extends AdminComponent
{
    public ?PlexSettingsModel $plexSettings = null;
    public $servers = [];
    public $selectedServer = null;
    public $selectedConnectionUrl;
    public $plexConnected = false;
    public $sharedUsers = [];
    public $isImporting = false;
    public $importProgress = 0;
    public $totalUsersToImport = 0;
    public $showingImportUsers = false;

    protected PlexService $plexService;

    protected function getListeners()
    {
        return [
            'server-selected' => '$refresh',
            'plex-connected' => 'handlePlexConnected'
        ];
    }

    public function boot(PlexService $plexService)
    {
        $this->plexService = $plexService;
    }

    public function mount(...$args)
    {
        parent::mount(...$args);
        $this->loadPlexSettings();
    }

    protected function loadPlexSettings()
    {
        try {
            Log::info('Loading Plex settings');
            $this->plexSettings = PlexSettingsModel::instance();
            Log::info('Plex settings loaded', ['settings' => $this->plexSettings->toArray()]);
            
            if ($this->plexSettings && $this->plexSettings->access_token) {
                $this->plexConnected = true;
                Log::info('Plex is connected, getting servers');
                
                // Get servers from the Plex service
                $plexServers = $this->plexService->getServers($this->plexSettings->access_token);
                Log::info('Retrieved servers from Plex', ['count' => count($plexServers), 'servers' => $plexServers]);
                
                // Transform the servers data to ensure correct structure
                $this->servers = collect($plexServers)->map(function($server) {
                    Log::info('Processing server', ['server' => $server]);
                    return [
                        'name' => $server['name'],
                        'machineIdentifier' => $server['machine_identifier'],
                        'version' => $server['version'] ?? null,
                        'connections' => $server['connections'] ?? [],
                        'owned' => $server['owned'] ?? false,
                        'accessToken' => $server['access_token'] ?? null
                    ];
                })->all();
                
                Log::info('Transformed servers data', ['servers' => $this->servers]);
                
                if ($this->plexSettings->machine_identifier) {
                    Log::info('Setting selected server from saved settings', [
                        'machine_identifier' => $this->plexSettings->machine_identifier,
                        'connection_url' => $this->plexSettings->connection_url
                    ]);
                    $this->selectedServer = $this->plexSettings->machine_identifier;
                    $this->selectedConnectionUrl = $this->plexSettings->connection_url;
                }
                
                session(['plex_servers' => $this->servers]);
            } else {
                Log::info('Plex is not connected or no access token found', [
                    'has_settings' => isset($this->plexSettings),
                    'has_token' => $this->plexSettings ? isset($this->plexSettings->access_token) : false,
                    'token' => $this->plexSettings ? $this->plexSettings->access_token : null
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to load Plex settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->servers = [];
        }
    }

    public function selectServer($machineIdentifier)
    {
        try {
            Log::info('Selecting server', ['machineIdentifier' => $machineIdentifier]);
            
            // Get server from the servers list
            $server = collect($this->servers)->first(function($server) use ($machineIdentifier) {
                return $server['machineIdentifier'] === $machineIdentifier;
            });

            if (!$server) {
                Log::error('Selected server not found', ['machineIdentifier' => $machineIdentifier]);
                session()->flash('error', 'Selected server not found.');
                return;
            }

            Log::info('Found server', ['server' => $server]);

            // Get all available connections for the server
            $connections = $server['connections'] ?? [];
            
            // Try to find the best connection URL
            $connectionUrl = null;
            
            // First try to find a local connection
            $localConnection = collect($connections)->first(function($conn) {
                return isset($conn['local']) && $conn['local'] === true;
            });
            
            if ($localConnection && isset($localConnection['uri'])) {
                $connectionUrl = $localConnection['uri'];
                Log::info('Using local connection', ['url' => $connectionUrl]);
            }

            // If no local connection, use the first available connection
            if (!$connectionUrl && !empty($connections)) {
                $firstConnection = $connections[0];
                $connectionUrl = $firstConnection['uri'] ?? null;
                Log::info('Using first available connection', ['url' => $connectionUrl]);
            }

            if (!$connectionUrl) {
                Log::error('No valid connection URL found');
                session()->flash('error', 'No valid connection URL found for this server.');
                return;
            }

            // Update plex settings
            $this->plexSettings->machine_identifier = $machineIdentifier;
            $this->plexSettings->server_name = $server['name'];
            $this->plexSettings->server_version = $server['version'] ?? null;
            $this->plexSettings->connection_url = $connectionUrl;
            $this->plexSettings->server_access_token = $server['accessToken'] ?? null;
            $this->plexSettings->save();

            Log::info('Updated plex settings', [
                'machine_identifier' => $machineIdentifier,
                'server_name' => $server['name'],
                'connection_url' => $connectionUrl
            ]);

            // Store in session
            session(['selected_server' => $machineIdentifier]);
            session(['plex_servers' => $this->servers]);
            
            // Update the component state
            $this->selectedServer = $machineIdentifier;
            $this->selectedConnectionUrl = $connectionUrl;

            session()->flash('success', 'Server selected successfully.');
            $this->dispatch('server-selected', $machineIdentifier);
            
        } catch (\Exception $e) {
            Log::error('Failed to select server', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to select server. Please try again.');
        }
    }

    public function disconnectPlex()
    {
        $this->plexSettings->clearSettings();
        $this->plexConnected = false;
        $this->dispatch('saved');
        session()->flash('message', 'Successfully disconnected from Plex.');
    }

    public function getPlexAuthUrlProperty()
    {
        return route('admin.plex.connect');
    }

    protected function getCategories()
    {
        return [
            [
                'name' => 'Settings',
                'icon' => 'cog',
                'links' => [
                    [
                        'name' => 'General',
                        'route' => 'admin.index',
                        'active' => request()->routeIs('admin.index')
                    ],
                    [
                        'name' => 'Plex',
                        'route' => 'admin.plex',
                        'active' => request()->routeIs('admin.plex')
                    ]
                ]
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.plex-settings');
    }

    public function refreshComponent()
    {
        Log::info('Refreshing PlexSettings component');
        $this->loadPlexSettings();
    }

    public function handlePlexConnected()
    {
        Log::info('Handling plex-connected event');
        $this->loadPlexSettings();
        $this->dispatch('$refresh');
    }
} 