<?php

use Illuminate\Support\Facades\Route;
use Stickee\Laravel2fa\Http\Controllers\Laravel2faController;

Route::post('confirm', Laravel2faController::class . '@confirm')->name('confirm');
Route::get('start-authentication', Laravel2faController::class . '@startAuthentication')->name('start-authentication');
Route::get('authenticate', Laravel2faController::class . '@authenticate')->name('authenticate');
Route::post('do-authentication', Laravel2faController::class . '@doAuthentication')->name('do-authentication');
Route::post('logout', Laravel2faController::class . '@logout')->name('logout');
