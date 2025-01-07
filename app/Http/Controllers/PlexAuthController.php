<?php

namespace App\Http\Controllers;

use App\Models\PlexSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PlexAuthController extends Controller
{
    protected $plexAuthUrl = 'https://app.plex.tv/auth#';
    protected $plexApiUrl = 'https://plex.tv/api/v2';

    protected function getClientId()
    {
        $clientId = Cache::get('plex_client_id');
        if (!$clientId) {
            $clientId = config('app.name') . '-' . Str::uuid()->toString();
            Cache::forever('plex_client_id', $clientId);
        }
        return $clientId;
    }

    protected function getPlexHeaders($additionalHeaders = [])
    {
        return array_merge([
            'Accept' => 'application/json',
            'X-Plex-Client-Identifier' => $this->getClientId(),
            'X-Plex-Product' => config('app.name'),
            'X-Plex-Version' => '1.0.0',
            'X-Plex-Platform' => 'Web',
            'X-Plex-Platform-Version' => '1.0.0',
            'X-Plex-Device' => 'Browser',
            'X-Plex-Device-Name' => config('app.name'),
            'X-Plex-Device-Screen-Resolution' => '1920x1080',
            'X-Plex-Language' => 'en'
        ], $additionalHeaders);
    }

    protected function getAuthUrl()
    {
        try {
            Log::info('Generating Plex auth URL');
            
            // Create a PIN
            $response = Http::withoutVerifying()
                ->withHeaders($this->getPlexHeaders())
                ->post('https://plex.tv/api/v2/pins', [
                    'strong' => true,
                    'X-Plex-Product' => config('app.name'),
                    'X-Plex-Client-Identifier' => $this->getClientId()
                ]);

            if (!$response->successful()) {
                Log::error('Failed to create Plex PIN', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Failed to create Plex PIN: ' . $response->body());
            }

            $data = $response->json();
            $pinId = $data['id'];
            $pinCode = $data['code'];

            Log::info('Created Plex PIN', [
                'pinId' => $pinId,
                'pinCode' => $pinCode
            ]);

            // Store the PIN ID for later verification
            Cache::put('plex_pin_id', $pinId, now()->addMinutes(10));

            // Build the auth URL
            $params = [
                'clientID' => $this->getClientId(),
                'code' => $pinCode,
                'context[device][product]' => config('app.name'),
                'context[device][environment]' => 'bundled',
                'context[device][layout]' => 'desktop',
                'context[device][platform]' => 'Web',
                'context[device][device]' => config('app.name'),
            ];

            $authUrl = 'https://app.plex.tv/auth#?' . http_build_query($params);
            Log::info('Generated Plex auth URL', ['url' => $authUrl]);

            return $authUrl;
        } catch (\Exception $e) {
            Log::error('Error generating Plex auth URL', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    public function connect()
    {
        $authUrl = $this->getAuthUrl();
        if (!$authUrl) {
            return redirect()->route('admin.plex')->with('error', 'Failed to initiate Plex authentication.');
        }
        return response()->json(['authUrl' => $authUrl]);
    }

    public function callback(Request $request)
    {
        try {
            Log::info('Plex callback received', [
                'request' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            // Get the stored PIN ID
            $pinId = Cache::pull('plex_pin_id');
            Log::info('Retrieved PIN ID from cache', ['pinId' => $pinId]);
            
            if (!$pinId) {
                throw new \Exception('No PIN ID found. Please try connecting again.');
            }

            // Check the PIN status with retries
            $maxAttempts = 5;
            $attempt = 0;
            $data = null;

            while ($attempt < $maxAttempts) {
                Log::info('Attempting to verify PIN', ['attempt' => $attempt + 1]);
                
                $response = Http::withoutVerifying()
                    ->withHeaders($this->getPlexHeaders())
                    ->get("https://plex.tv/api/v2/pins/{$pinId}");

                if (!$response->successful()) {
                    Log::warning('PIN verification request failed', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    $attempt++;
                    sleep(2);
                    continue;
                }

                $data = $response->json();
                Log::info('PIN verification response', ['data' => $data]);
                
                if (isset($data['authToken']) && !empty($data['authToken'])) {
                    Log::info('Auth token received', ['token_length' => strlen($data['authToken'])]);
                    break;
                }

                $attempt++;
                sleep(2);
            }

            if (!$data || !isset($data['authToken']) || empty($data['authToken'])) {
                throw new \Exception('No auth token received from Plex after ' . $maxAttempts . ' attempts.');
            }

            // Get user info using the auth token
            Log::info('Getting user info with auth token');
            $userResponse = Http::withoutVerifying()
                ->withHeaders($this->getPlexHeaders(['X-Plex-Token' => $data['authToken']]))
                ->get('https://plex.tv/api/v2/user');

            if (!$userResponse->successful()) {
                Log::error('Failed to get user info', [
                    'status' => $userResponse->status(),
                    'body' => $userResponse->body()
                ]);
                throw new \Exception('Failed to get user info: ' . $userResponse->body());
            }

            $userData = $userResponse->json();
            Log::info('Successfully got user info', ['userData' => $userData]);

            // Save the Plex settings
            $plexSettings = PlexSettings::instance();
            Log::info('Current Plex settings before update', ['settings' => $plexSettings->toArray()]);

            $plexSettings->access_token = $data['authToken'];
            $plexSettings->username = $userData['username'];
            $plexSettings->email = $userData['email'];
            $saved = $plexSettings->save();

            Log::info('Attempted to save Plex settings', [
                'saved' => $saved,
                'settings' => $plexSettings->fresh()->toArray()
            ]);

            if (!$saved) {
                throw new \Exception('Failed to save Plex settings to database.');
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully connected to Plex!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in Plex callback', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to complete Plex authentication: ' . $e->getMessage()
            ], 500);
        }
    }

    public function disconnect()
    {
        try {
            $plexSettings = PlexSettings::instance();
            $plexSettings->clearSettings();

            return redirect()->route('admin.plex')->with('success', 'Successfully disconnected from Plex.');
        } catch (\Exception $e) {
            Log::error('Error disconnecting from Plex: ' . $e->getMessage());
            return redirect()->route('admin.plex')->with('error', 'Failed to disconnect from Plex.');
        }
    }
}