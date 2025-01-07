<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PlexService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PlexAuthController extends Controller
{
    protected $plexService;

    public function __construct(PlexService $plexService)
    {
        $this->plexService = $plexService;
    }

    public function redirect()
    {
        try {
            \Log::info('Starting Plex auth redirect');
            $authUrl = $this->plexService->getAuthUrl('auth.plex.callback');
            \Log::info('Generated Plex auth URL', ['url' => $authUrl]);
            return redirect()->away($authUrl);
        } catch (\Exception $e) {
            \Log::error('Error in Plex auth redirect', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to connect to Plex. Please try again.');
        }
    }

    public function callback(Request $request)
    {
        try {
            // Get the PIN ID from cache
            $pinId = cache('plex_pin_id');
            if (!$pinId) {
                throw new \Exception('Invalid authentication attempt');
            }

            // Get access token using the PIN
            $accessToken = $this->plexService->getAccessToken($pinId);
            if (!$accessToken) {
                throw new \Exception('Failed to get access token');
            }

            // Get user info from Plex
            $plexUser = $this->plexService->getUserInfo($accessToken);
            if (!$plexUser) {
                throw new \Exception('Failed to get user info from Plex');
            }

            // Find or create user
            $user = User::updateOrCreate(
                ['plex_id' => $plexUser['id']],
                [
                    'name' => $plexUser['title'] ?? $plexUser['username'],
                    'email' => $plexUser['email'],
                    'plex_username' => $plexUser['username'],
                    'plex_thumb' => $plexUser['thumb'] ?? null,
                    'password' => Hash::make(Str::random(32)), // Random password as they'll use Plex to login
                ]
            );

            // Log the user in
            Auth::login($user);

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to authenticate with Plex: ' . $e->getMessage());
        }
    }
}
