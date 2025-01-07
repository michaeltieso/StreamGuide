<?php

namespace App\Livewire;

use App\Models\SiteSetting;
use App\Models\Link;
use App\Models\GuideCategory;
use App\Models\FaqCategory;
use App\Models\PlexSettings;
use App\Services\PlexService;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Cache;

#[Layout('layouts.app')]
class Home extends Component
{
    public $serverTitle;
    public $serverDescription;
    public $links;
    public $categories;
    public $faqCategories;
    public $serverStats = [
        'status' => 'unknown',
        'version' => 'Unknown',
        'activeUsers' => 0,
        'libraries' => [
            'movies' => 0,
            'shows' => 0,
            'music' => 0
        ]
    ];
    public $serverStatus = 'unknown';
    public $errorMessage;
    public $readyToLoad = false;
    public $maintenanceEnabled = false;
    public $maintenanceStart;
    public $maintenanceEnd;
    public $maintenanceMessage;

    protected $plexService;

    protected $listeners = [
        'settings-saved' => 'refreshComponent',
        'maintenance-settings-saved' => 'refreshComponent'
    ];

    public function refreshComponent()
    {
        // Clear local cache
        Cache::forget('server_title');
        Cache::forget('server_description');
        Cache::forget('maintenance_settings');
        
        // Re-mount the component to refresh data
        $this->mount();
    }

    public function boot(PlexService $plexService)
    {
        $this->plexService = $plexService;
    }

    public function mount(): void
    {
        // Cache frequently accessed settings for 24 hours since they rarely change
        $this->serverTitle = Cache::remember('server_title', 86400, function() {
            return SiteSetting::get('server_title') ?? 'Welcome to Our Plex Server';
        });
        
        $this->serverDescription = Cache::remember('server_description', 86400, function() {
            return SiteSetting::get('server_description') ?? 'A place to stream all your favorite content';
        });

        // Cache links and categories for 1 hour
        $this->links = Cache::remember('navigation_links', 3600, function() {
            return Link::orderBy('order')->get();
        });
        
        $this->faqCategories = Cache::remember('faq_categories', 3600, function() {
            return FaqCategory::with('faqs')->orderBy('order')->get();
        });
        
        $this->categories = Cache::remember('guide_categories', 3600, function() {
            return GuideCategory::with(['guides' => function($query) {
                $query->orderBy('order');
            }])->orderBy('order')->get();
        });

        // Cache maintenance settings for 5 minutes since they're more time-sensitive
        $maintenanceSettings = Cache::remember('maintenance_settings', 300, function() {
            return [
                'enabled' => SiteSetting::get('maintenance_enabled', false),
                'start' => SiteSetting::get('maintenance_start'),
                'end' => SiteSetting::get('maintenance_end'),
                'message' => SiteSetting::get('maintenance_message')
            ];
        });

        $this->maintenanceEnabled = $maintenanceSettings['enabled'];
        $this->maintenanceStart = $maintenanceSettings['start'];
        $this->maintenanceEnd = $maintenanceSettings['end'];
        $this->maintenanceMessage = $maintenanceSettings['message'];
    }

    public function init()
    {
        $this->readyToLoad = true;
        $this->updateServerStats();
    }

    public function updateServerStats(): void
    {
        try {
            // Cache server stats for 5 minutes to reduce API calls
            $stats = Cache::remember('server_stats', 300, function () {
                set_time_limit(60);
                Log::info('Updating server stats from Livewire component');
                $server = $this->plexService->findSelectedServer();
                
                if (!$server || !isset($server['connection_url']) || !isset($server['access_token'])) {
                    Log::warning('No server selected or missing connection details', [
                        'server' => $server ?? null
                    ]);
                    return null;
                }

                return rescue(function () use ($server) {
                    return $this->plexService->getServerStatistics(
                        $server['connection_url'],
                        $server['access_token']
                    );
                }, function ($exception) {
                    Log::error('Timeout or error getting server statistics', [
                        'error' => $exception->getMessage()
                    ]);
                    return null;
                }, false);
            });

            if (empty($stats)) {
                $this->setOfflineStats();
                return;
            }

            $this->serverStats = [
                'status' => 'online',
                'version' => $stats['version'],
                'activeUsers' => $stats['activeUsers'],
                'libraries' => [
                    'movies' => $stats['libraries']['movies'],
                    'shows' => $stats['libraries']['shows'],
                    'music' => $stats['libraries']['music']
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Failed to update server stats in Livewire component', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->setOfflineStats('error');
        }
    }

    private function setOfflineStats(string $status = 'offline'): void
    {
        $this->serverStats = [
            'status' => $status,
            'version' => 'Unknown',
            'activeUsers' => 0,
            'libraries' => [
                'movies' => 0,
                'shows' => 0,
                'music' => 0
            ]
        ];
    }

    public function getServerStatistics()
    {
        try {
            $server = $this->plexService->findSelectedServer();
            if (!$server || !isset($server['connection_url'])) {
                return null;
            }

            return $this->plexService->getServerStatistics(
                $server['connection_url'],
                $server['access_token']
            );
        } catch (\Exception $e) {
            Log::error('Failed to get server statistics in Home', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    // Add cache clearing on settings update
    protected function clearHomeCache(): void
    {
        Cache::forget('server_title');
        Cache::forget('server_description');
        Cache::forget('navigation_links');
        Cache::forget('faq_categories');
        Cache::forget('guide_categories');
        Cache::forget('server_stats');
    }

    public function render(): View
    {
        return view('livewire.home', [
            'categories' => $this->categories,
            'faqCategories' => $this->faqCategories
        ]);
    }
}