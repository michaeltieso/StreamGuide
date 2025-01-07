<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use LukeHagar\Plex_API\PlexAPI;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use GuzzleHttp\Client;
use App\Models\PlexSettings;

class PlexService
{
    protected $clientIdentifier;
    protected $sdk;

    public function __construct()
    {
        $this->clientIdentifier = Cache::rememberForever('plex_client_identifier', function () {
            return Uuid::uuid4()->toString();
        });
    }

    protected function getHttpClient(array $options = [])
    {
        $http = Http::withHeaders([
            'X-Plex-Client-Identifier' => (string) $this->clientIdentifier,
            'X-Plex-Product' => (string) config('app.name'),
            'Accept' => 'application/json'
        ]);

        if (app()->environment('local')) {
            $http->withoutVerifying();
        }

        return $http->withOptions($options);
    }

    public function getAuthUrl(string $returnRoute = 'admin.plex.callback'): string
    {
        // First, create a PIN
        try {
            Log::info('Creating Plex PIN for authentication');
            $response = $this->getHttpClient()
                ->post('https://plex.tv/api/v2/pins', [
                    'strong' => true
                ]);

            if (!$response->successful()) {
                Log::error('Failed to create Plex PIN', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Failed to create Plex PIN');
            }

            $data = $response->json();
            $pinId = $data['id'];
            $pinCode = $data['code'];

            Log::info('Successfully created Plex PIN', [
                'pinId' => $pinId,
                'pinCode' => $pinCode
            ]);

            // Store the PIN ID for later verification
            Cache::put('plex_pin_id', $pinId, now()->addMinutes(10));

            // Generate the auth URL with the PIN code
            $params = [
                'clientID' => (string) $this->clientIdentifier,
                'code' => $pinCode,
                'context[device][product]' => (string) config('app.name'),
                'context[device][version]' => '1.0.0',
                'context[device][platform]' => 'Web',
                'context[device][platformVersion]' => '1.0.0',
                'context[device][device]' => 'Browser',
                'context[device][deviceName]' => (string) config('app.name'),
                'context[device][model]' => 'hosted',
                'context[device][vendor]' => 'Browser',
                'forwardUrl' => (string) route($returnRoute)
            ];

            $authUrl = 'https://app.plex.tv/auth#?' . http_build_query($params);
            Log::info('Generated Plex auth URL', [
                'url' => $authUrl,
                'returnRoute' => $returnRoute
            ]);

            return $authUrl;
        } catch (\Exception $e) {
            Log::error('Error generating Plex auth URL', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getAccessToken(string $code = null): ?string
    {
        // Get the stored PIN ID
        $pinId = Cache::pull('plex_pin_id');
        
        if (!$pinId) {
            return null;
        }

        try {
            // Check the PIN status
            $response = $this->getHttpClient()
                ->get("https://plex.tv/api/v2/pins/{$pinId}");

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['authToken']) && !empty($data['authToken'])) {
                    return $data['authToken'];
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to get Plex access token', [
                'error' => $e->getMessage(),
                'pinId' => $pinId
            ]);
        }

        return null;
    }

    protected function getSdk(?string $token = null)
    {
        $builder = PlexAPI::builder();
        
        if ($token) {
            $builder = $builder->setSecurity($token);
        }

        // Note: Currently the SDK doesn't provide direct SSL verification control
        // We'll need to handle SSL issues at the application level if they occur
        
        return $builder->build();
    }

    public function getCurrentUser(string $accessToken): ?array
    {
        try {
            Log::info('Getting current user info');
            
            // Make a direct HTTP request to get user info, skipping SSL verification
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'X-Plex-Token' => $accessToken,
                    'Accept' => 'application/json'
                ])->get('https://plex.tv/api/v2/user');

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Successfully retrieved user info', ['data' => $data]);
                
                return [
                    'id' => $data['id'],
                    'uuid' => $data['uuid'],
                    'email' => $data['email'],
                    'username' => $data['username'],
                    'title' => $data['title'],
                    'thumb' => $data['thumb'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Failed to get Plex user info', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return null;
    }

    private function getClientId(): string
    {
        return config('services.plex.client_id') ?? '7567274f-b3fc-4d7e-8d1b-d33bfe8aca9f';
    }

    public function getServers($token): array
    {
        \Log::info('Getting servers list with token', ['tokenLength' => strlen($token)]);
        
        try {
            $clientId = $this->getClientId();
            \Log::info('Using client ID', ['client_id' => $clientId]);
            
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'X-Plex-Token' => $token,
                    'X-Plex-Client-Identifier' => $clientId,
                    'Accept' => 'application/json',
                    'X-Plex-Product' => config('app.name', 'StreamGuide'),
                    'X-Plex-Version' => '1.0.0',
                    'X-Plex-Platform' => 'Web',
                    'X-Plex-Platform-Version' => '1.0.0',
                    'X-Plex-Device' => 'Browser',
                    'X-Plex-Device-Name' => config('app.name', 'StreamGuide'),
                    'X-Plex-Model' => 'hosted',
                    'X-Plex-Vendor' => 'Browser'
                ])->get('https://plex.tv/api/v2/resources');
            
            if (!$response->successful()) {
                throw new \Exception('Failed to get servers: ' . $response->body());
            }
            
            \Log::info('Raw server response', [
                'status' => $response->status(),
                'dataCount' => count($response->json())
            ]);
            
            $servers = [];
            foreach ($response->json() as $resource) {
                if ($resource['provides'] === 'server') {
                    \Log::info('Processed server data', [
                        'name' => $resource['name'],
                        'machine_identifier' => $resource['clientIdentifier']
                    ]);
                    
                    $connections = collect($resource['connections'])->map(function($conn) {
                        return [
                            'uri' => $conn['uri'],
                            'local' => $conn['local'],
                            'relay' => $conn['relay'] ?? false,
                            'ipv6' => $conn['ipv6'] ?? false
                        ];
                    })->toArray();
                    
                    $servers[] = [
                        'name' => $resource['name'],
                        'machine_identifier' => $resource['clientIdentifier'],
                        'access_token' => $resource['accessToken'],
                        'connection_url' => $connections[0]['uri'] ?? null,
                        'connections' => $connections,
                        'version' => $resource['productVersion'] ?? null,
                        'owned' => $resource['owned'] ?? false
                    ];
                }
            }
            
            \Log::info('Processed servers', [
                'count' => count($servers),
                'servers' => collect($servers)->map(fn($s) => [
                    'name' => $s['name'],
                    'machine_identifier' => $s['machine_identifier']
                ])
            ]);
            
            return $servers;
        } catch (\Exception $e) {
            \Log::error('Failed to get servers', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getServerInfo(string $accessToken, string $serverUrl): ?array
    {
        try {
            // Get server capabilities
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'X-Plex-Token' => $accessToken,
                    'X-Plex-Client-Identifier' => $this->clientIdentifier,
                    'Accept' => 'application/json'
                ])
                ->get($serverUrl);

            Log::info('Server capabilities response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);
            
            // Get server sessions
            $sessionsResponse = Http::withoutVerifying()
                ->withHeaders([
                    'X-Plex-Token' => $accessToken,
                    'X-Plex-Client-Identifier' => $this->clientIdentifier,
                    'Accept' => 'application/json'
                ])
                ->get($serverUrl . '/status/sessions');

            $sessions = [];
            if ($sessionsResponse->successful()) {
                $sessions = $sessionsResponse->json()['MediaContainer']['Metadata'] ?? [];
            }

            Log::info('Server sessions response', [
                'status' => $sessionsResponse->status(),
                'sessionCount' => count($sessions)
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $mediaContainer = $data['MediaContainer'] ?? null;
                
                return [
                    'status' => 'online',
                    'version' => $mediaContainer['version'] ?? 'unknown',
                    'platform' => $mediaContainer['platform'] ?? 'unknown',
                    'platform_version' => $mediaContainer['platformVersion'] ?? 'unknown',
                    'machine_identifier' => $mediaContainer['machineIdentifier'] ?? null,
                    'sessions' => $sessions
                ];
            }
        } catch (\Exception $e) {
            Log::error('Failed to get Plex server info', [
                'error' => $e->getMessage(),
                'serverUrl' => $serverUrl
            ]);
        }

        return [
            'status' => 'offline',
            'version' => 'unknown',
            'sessions' => []
        ];
    }

    public function getSharedUsers(string $accessToken, string $machineIdentifier): array
    {
        try {
            Log::info('Getting shared users for server', [
                'machineIdentifier' => $machineIdentifier
            ]);

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'X-Plex-Token' => $accessToken,
                    'Accept' => 'application/json'
                ])->get('https://plex.tv/api/servers/' . $machineIdentifier . '/shared_servers');

            if ($response->successful()) {
                $data = $response->json();
                $users = [];

                // The response includes SharedServer objects that contain user information
                foreach ($data['MediaContainer']['SharedServer'] ?? [] as $sharedServer) {
                    if (isset($sharedServer['userID'])) {
                        // Get detailed user information
                        $userResponse = Http::withoutVerifying()
                            ->withHeaders([
                                'X-Plex-Token' => $accessToken,
                                'Accept' => 'application/json'
                            ])->get('https://plex.tv/api/users/' . $sharedServer['userID']);

                        if ($userResponse->successful()) {
                            $userData = $userResponse->json();
                            $user = $userData['User'] ?? null;
                            
                            if ($user) {
                                $users[] = [
                                    'id' => $user['id'],
                                    'uuid' => $user['uuid'] ?? null,
                                    'email' => $user['email'] ?? null,
                                    'username' => $user['username'],
                                    'title' => $user['title'],
                                    'thumb' => $user['thumb'] ?? null,
                                    'hasPassword' => $user['hasPassword'] ?? false,
                                    'joinedAt' => $user['joinedAt'] ?? null,
                                    'status' => $sharedServer['status'] ?? 'unknown',
                                    'acceptedAt' => $sharedServer['acceptedAt'] ?? null,
                                    'invitedAt' => $sharedServer['invitedAt'] ?? null,
                                    'allowSync' => $sharedServer['allowSync'] ?? false,
                                    'allowCameraUpload' => $sharedServer['allowCameraUpload'] ?? false,
                                    'allowChannels' => $sharedServer['allowChannels'] ?? false,
                                    'allowTuners' => $sharedServer['allowTuners'] ?? false
                                ];
                            }
                        }
                    }
                }

                Log::info('Retrieved shared users', [
                    'count' => count($users)
                ]);

                return $users;
            }

            Log::warning('Failed to get shared users', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting shared users', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return [];
    }

    public function getUserFriends(string $accessToken): array
    {
        try {
            Log::info('Getting Plex user friends');

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'X-Plex-Token' => $accessToken,
                    'X-Plex-Client-Identifier' => $this->getClientId(),
                    'Accept' => 'application/json'
                ])->get('https://plex.tv/api/v2/friends');

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Raw friends response', ['data' => $data]);
                
                $friends = [];
                foreach ($data ?? [] as $friend) {
                    $friends[] = [
                        'id' => $friend['id'],
                        'uuid' => $friend['uuid'] ?? null,
                        'title' => $friend['title'],
                        'username' => $friend['username'],
                        'email' => $friend['email'],
                        'thumb' => $friend['thumb'] ?? null,
                        'status' => $friend['status'],
                        'invitedAt' => null,
                        'acceptedAt' => null,
                        'allowSync' => true,
                        'allowCameraUpload' => false,
                        'allowChannels' => true,
                        'allowTuners' => false
                    ];
                }
                
                Log::info('Retrieved Plex friends', [
                    'count' => count($friends),
                    'response' => $data
                ]);

                return $friends;
            }

            Log::warning('Failed to get Plex friends', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return [];

        } catch (\Exception $e) {
            Log::error('Error getting Plex friends', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    public function getServerStatistics(string $connectionUrl, string $accessToken): array
    {
        try {
            Log::info('Getting server statistics', [
                'url' => $connectionUrl,
                'hasToken' => !empty($accessToken)
            ]);

            // Get server info
            $response = Http::withoutVerifying()
                ->withHeaders($this->getPlexHeaders(['X-Plex-Token' => $accessToken]))
                ->get($connectionUrl);

            if (!$response->successful()) {
                Log::error('Server info request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Failed to get server info: ' . $response->status());
            }

            $serverInfo = $response->json();
            Log::info('Got server info', [
                'version' => $serverInfo['MediaContainer']['version'] ?? 'unknown',
                'machineIdentifier' => $serverInfo['MediaContainer']['machineIdentifier'] ?? null
            ]);

            // Get library sections
            $sectionsResponse = Http::withoutVerifying()
                ->withHeaders($this->getPlexHeaders(['X-Plex-Token' => $accessToken]))
                ->get("{$connectionUrl}/library/sections");

            if (!$sectionsResponse->successful()) {
                Log::error('Library sections request failed', [
                    'status' => $sectionsResponse->status(),
                    'body' => $sectionsResponse->body()
                ]);
                throw new \Exception('Failed to get library sections: ' . $sectionsResponse->status());
            }

            $sections = $sectionsResponse->json();
            Log::info('Got library sections', [
                'count' => count($sections['MediaContainer']['Directory'] ?? [])
            ]);

            // Get active sessions
            $sessionsResponse = Http::withoutVerifying()
                ->withHeaders($this->getPlexHeaders(['X-Plex-Token' => $accessToken]))
                ->get("{$connectionUrl}/status/sessions");

            if (!$sessionsResponse->successful()) {
                Log::error('Sessions request failed', [
                    'status' => $sessionsResponse->status(),
                    'body' => $sessionsResponse->body()
                ]);
                throw new \Exception('Failed to get sessions: ' . $sessionsResponse->status());
            }

            $sessions = $sessionsResponse->json();
            Log::info('Got sessions', [
                'activeCount' => count($sessions['MediaContainer']['Metadata'] ?? [])
            ]);

            // Process library sections
            $libraries = [
                'movies' => 0,
                'shows' => 0,
                'music' => 0
            ];

            foreach ($sections['MediaContainer']['Directory'] ?? [] as $section) {
                try {
                    // Get the count for this section
                    $sectionResponse = Http::withoutVerifying()
                        ->withHeaders($this->getPlexHeaders(['X-Plex-Token' => $accessToken]))
                        ->get("{$connectionUrl}/library/sections/{$section['key']}/all");

                    if (!$sectionResponse->successful()) {
                        Log::error("Failed to get section data", [
                            'section' => $section['title'],
                            'status' => $sectionResponse->status(),
                            'body' => $sectionResponse->body()
                        ]);
                        continue;
                    }

                    $sectionData = $sectionResponse->json();
                    $totalSize = $sectionData['MediaContainer']['totalSize'] ?? 
                                $sectionData['MediaContainer']['size'] ?? 
                                count($sectionData['MediaContainer']['Metadata'] ?? []);

                    $type = strtolower($section['type']);

                    // Log the section details for debugging
                    Log::info("Processing library section", [
                        'title' => $section['title'],
                        'type' => $type,
                        'count' => $totalSize,
                        'key' => $section['key']
                    ]);

                    // Handle library types
                    switch ($type) {
                        case 'movie':
                            $libraries['movies'] += $totalSize;
                            break;
                        case 'show':
                            $libraries['shows'] += $totalSize;
                            break;
                        case 'artist':
                            $libraries['music'] += $totalSize;
                            break;
                    }

                    Log::info("Updated counts for section {$section['title']}", [
                        'type' => $type,
                        'count' => $totalSize,
                        'libraries' => $libraries
                    ]);
                } catch (\Exception $e) {
                    Log::error("Error processing section {$section['title']}", [
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            $result = [
                'status' => 'online',
                'version' => $serverInfo['MediaContainer']['version'] ?? 'Unknown',
                'activeUsers' => count($sessions['MediaContainer']['Metadata'] ?? []),
                'libraries' => $libraries
            ];

            Log::info('Returning server statistics', $result);
            return $result;

        } catch (\Exception $e) {
            Log::error('Failed to get server statistics', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'status' => 'error',
                'version' => 'Unknown',
                'activeUsers' => 0,
                'libraries' => [
                    'movies' => 0,
                    'shows' => 0,
                    'music' => 0
                ]
            ];
        }
    }

    protected function getPlexHeaders(array $additional = []): array
    {
        return array_merge([
            'X-Plex-Client-Identifier' => $this->clientIdentifier,
            'X-Plex-Product' => config('app.name', 'StreamGuide'),
            'Accept' => 'application/json'
        ], $additional);
    }

    protected function getToken(): string
    {
        $settings = PlexSettings::first();
        if (!$settings || !$settings->access_token) {
            throw new \Exception('No Plex token found');
        }
        return $settings->access_token;
    }

    public function findSelectedServer(): ?array
    {
        try {
            $plexSettings = PlexSettings::instance();
            
            Log::info('Finding selected server', [
                'hasAccessToken' => !empty($plexSettings->access_token),
                'hasMachineId' => !empty($plexSettings->machine_identifier)
            ]);

            if (!$plexSettings->access_token || !$plexSettings->machine_identifier) {
                Log::warning('Missing required Plex settings', [
                    'hasAccessToken' => !empty($plexSettings->access_token),
                    'hasMachineId' => !empty($plexSettings->machine_identifier)
                ]);
                return null;
            }

            $servers = $this->getServers($plexSettings->access_token);
            
            Log::info('Got servers list', [
                'count' => count($servers),
                'lookingFor' => $plexSettings->machine_identifier
            ]);

            $selectedServer = collect($servers)->first(function($server) use ($plexSettings) {
                return $server['machine_identifier'] === $plexSettings->machine_identifier;
            });

            if (!$selectedServer) {
                Log::warning('Selected server not found in servers list', [
                    'machineId' => $plexSettings->machine_identifier,
                    'availableServers' => collect($servers)->pluck('machine_identifier')
                ]);
                return null;
            }

            $result = [
                'connection_url' => $plexSettings->connection_url,
                'access_token' => $plexSettings->server_access_token ?? $plexSettings->access_token
            ];

            Log::info('Found selected server', [
                'hasConnectionUrl' => !empty($result['connection_url']),
                'hasAccessToken' => !empty($result['access_token'])
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to find selected server', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    public function validateToken(string $accessToken): bool
    {
        try {
            $response = $this->getHttpClient()
                ->withToken($accessToken)
                ->get('https://plex.tv/api/v2/user');

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Token validation failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}