<?php

use App\Models\Tenant;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {

        Route::view('/', 'welcome');

        Route::view('dashboard', 'dashboard')
            ->middleware(['auth', 'verified'])
            ->name('dashboard');

        Route::view('sites/manage/{site}', 'sites.manage')
            ->middleware(['auth', 'verified'])
            ->name('sites.manage');

        Route::view('profile', 'profile')
            ->middleware(['auth'])
            ->name('profile');

        Route::get('test', function () {
            Tenant::create([
                'id' => '2',
            ]);
        });
    });
}

require __DIR__.'/auth.php';
