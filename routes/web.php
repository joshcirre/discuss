<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::view('/', 'welcome');
        Volt::route('/create', 'create-discuss')->name('create-discuss');

    });
}
