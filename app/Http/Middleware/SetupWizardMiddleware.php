<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class SetupWizardMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // If no users exist, redirect to wizard unless already there
        if (User::count() === 0) {
            // Allow access to wizard
            if ($request->is('wizard*')) {
                return $next($request);
            }
            
            // Block access to login/register when no users exist
            if ($request->is('login*') || $request->is('register*')) {
                return redirect()->route('wizard');
            }
            
            // Redirect all other routes to wizard
            return redirect()->route('wizard');
        }
        
        // If users exist and trying to access wizard, redirect home
        if ($request->is('wizard*')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
