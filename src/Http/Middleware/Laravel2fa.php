<?php

namespace Stickee\Laravel2fa\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Stickee\Laravel2fa\Http\Controllers\Laravel2faController;
use Stickee\Laravel2fa\Models\Laravel2fa as Laravel2faModel;
use Stickee\Laravel2fa\Services\Laravel2faService;

class Laravel2fa
{
    /**
     * Handle an incoming request
     *
     * @param \Illuminate\Http\Request $request The request
     * @param \Closure $next The next method
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Don't do 2FA if it's disabled or the user is not logged in
        if (!config('laravel-2fa.enabled') || Auth::guest()) {
            return $next($request);
        }

        // Don't do 2FA for 2FA setup / validation routes
        if (Str::startsWith($request->route()->action['prefix'] ?? '', config('laravel-2fa.routes_prefix'))) {
            return $next($request);
        }

        /**
         * TODO: make this more abstract?
         * @var \Illuminate\Database\Eloquent\Model
         */
        $user = Auth::user();
        $laravel2fa = Laravel2faModel::getByModel($user) ?? Laravel2faModel::createByModel($user);

        // If the user doesn't have 2FA enabled...
        if (!$laravel2fa->laravel2fa_enabled) {
            // ... if it's required, force them to enable it
            if (config('laravel-2fa.required')) {
                $controller = app(Laravel2faController::class);

                return response(app()->call([$controller, 'register']));
            } else {
                return $next($request);
            }
        }

        $service = app(Laravel2faService::class);

        if ($service->needsToAuthenticate() && !$service->isAuthenticated()) {
            $controller = app(Laravel2faController::class);

            return response(app()->call([$controller, 'authenticate']));
        }

        return $next($request);
    }
}
