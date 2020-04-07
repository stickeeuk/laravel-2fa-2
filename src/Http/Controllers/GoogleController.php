<?php

namespace Stickee\Laravel2fa\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stickee\Laravel2fa\Http\Requests\ConfirmRequest;
use Stickee\Laravel2fa\Services\Laravel2faService;

class GoogleController extends Controller
{
    /**
     * Show the 2fa registration page
     *
     * @param \Illuminate\Http\Request $request The request
     * @param \Stickee\Laravel2fa\Services\Laravel2faService $service The 2FA service
     *
     * @return Illuminate\View\View
     */
    public function register(Request $request, Laravel2faService $service)
    {
        $driverName = last(explode('/', $request->route()->action['prefix']));
        $driver = $service->make($driverName);
        $data = $driver->getRegistrationViewData();
        $data['driverName'] = $driverName;

        return view('laravel-2fa::drivers.google-register', $data);
    }

    /**
     * Confirm activating 2FA
     *
     * @param \Stickee\Laravel2fa\Http\Requests\ConfirmRequest $request The request
     * @param \Stickee\Laravel2fa\Services\Laravel2faService $service The 2FA service
     *
     * @return \Illuminate\Http\Redirect
     */
    public function confirm(ConfirmRequest $request, Laravel2faService $service)
    {
        $driverName = last(explode('/', $request->route()->action['prefix']));
        $service->enable($driverName);
        $service->setAuthenticated(true);

        return redirect(config('laravel-2fa.redirect_after_login', '/'));
    }
}
