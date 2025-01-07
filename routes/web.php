<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\Guide;
use App\Livewire\Admin\Settings;
use App\Livewire\SetupWizard;
use App\Http\Middleware\SetupWizardMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\PlexAuthController;
use App\Livewire\Admin\PlexSettings;

// Setup wizard route - accessible without auth
Route::get('/wizard', SetupWizard::class)->name('wizard');

// All other routes require authentication
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Public routes
    Route::get('/', App\Livewire\Home::class)->name('home');
    Route::get('/guide', App\Livewire\Guide::class)->name('guide');
    Route::get('/guide/{slug}', App\Livewire\Guide\Show::class)->name('guide.show');
    
    // Admin routes
    Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/', App\Livewire\Admin\Settings::class)->name('index');
            Route::get('/about', App\Livewire\Admin\About::class)->name('about');
            Route::get('/debug', App\Livewire\Admin\Debug::class)->name('debug');
            Route::get('/import', App\Livewire\Admin\Import::class)->name('import');
            Route::get('/guide', App\Livewire\Admin\GuideManager::class)->name('guide');
            Route::get('/guide-categories', App\Livewire\Admin\GuideCategoryManager::class)->name('guide-categories');
            Route::get('/links', App\Livewire\Admin\LinkManager::class)->name('links');
            Route::get('/faqs', App\Livewire\Admin\FaqManager::class)->name('faqs');
            Route::get('/faq-categories', App\Livewire\Admin\FaqCategoryManager::class)->name('faq-categories');
            Route::get('/users', App\Livewire\Admin\UserManager::class)->name('users');
            Route::get('/plex', App\Livewire\Admin\PlexSettings::class)->name('plex');

            // Plex Authentication Routes
            Route::prefix('plex')->name('plex.')->group(function () {
                Route::get('/connect', [PlexAuthController::class, 'connect'])->name('connect');
                Route::get('/callback', [PlexAuthController::class, 'callback'])->name('callback');
                Route::post('/disconnect', [PlexAuthController::class, 'disconnect'])->name('disconnect');
            });
        });

    // Plex Authentication Routes
    Route::get('/auth/plex', [App\Http\Controllers\Auth\PlexAuthController::class, 'redirect'])->name('auth.plex');
    Route::get('/auth/plex/callback', [App\Http\Controllers\Auth\PlexAuthController::class, 'callback'])->name('auth.plex.callback');
});

// Add this near the top of the file, after any use statements
Route::middleware(['guest'])->group(function () {
    Route::get('/register', function() {
        return redirect('/login');
    });
    Route::post('/register', function() {
        abort(404);
    });
});
