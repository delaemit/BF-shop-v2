<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Webkul\SocialLogin\Http\Controllers\LoginController;

Route::controller(LoginController::class)->middleware(['web', 'shop'])->prefix('customer/social-login/{provider}')->group(function (): void {
    Route::get('', 'redirectToProvider')->name('customer.social-login.index');

    Route::get('callback', 'handleProviderCallback')->name('customer.social-login.callback');
});
