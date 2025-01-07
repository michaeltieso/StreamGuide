<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TmdbService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.themoviedb.org/3';
    protected $imageBaseUrl = 'https://image.tmdb.org/t/p/original';

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.key');
    }

    public function getRandomBackdrop(): ?string
    {
        try {
            $url = "{$this->baseUrl}/discover/movie";
            $params = [
                'api_key' => $this->apiKey,
                'sort_by' => 'popularity.desc',
                'include_adult' => false,
                'include_video' => false,
                'page' => 1
            ];

            $response = Http::withoutVerifying()->get($url, $params);

            if (!$response->successful()) {
                return null;
            }

            $movies = collect($response->json()['results'])
                ->filter(function ($item) {
                    return !empty($item['backdrop_path']);
                });

            if ($movies->isEmpty()) {
                return null;
            }

            $movie = $movies->random();
            return $this->imageBaseUrl . $movie['backdrop_path'];

        } catch (\Exception $e) {
            return null;
        }
    }
}
