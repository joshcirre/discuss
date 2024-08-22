<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Models\Site;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyBySubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::get('/', function () {
        dd(\App\Models\User::all());

        return 'This is your multi-tenant application. The id of the current tenant is '.tenant('id');
    });

    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::view('sites/manage/{site}', 'sites.manage')
        ->middleware(['auth', 'verified'])
        ->name('sites.manage');

    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');

    Route::middleware('auth')->group(function () {
        Volt::route('verify-email', 'pages.auth.verify-email')
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Volt::route('confirm-password', 'pages.auth.confirm-password')
            ->name('password.confirm');

    });
    Route::middleware('guest')->group(function () {
        Volt::route('register', 'pages.auth.register')
            ->name('register');

        Volt::route('login', 'pages.auth.login')
            ->name('login');

        Volt::route('forgot-password', 'pages.auth.forgot-password')
            ->name('password.request');

        Volt::route('reset-password/{token}', 'pages.auth.reset-password')
            ->name('password.reset');
    });

    Route::get('/site/{site:subdomain}', function (Site $site) {
        $posts = $site->posts()->with('user')->latest()->paginate(15);

        return view('public.sites.home', [
            'site' => $site,
            'posts' => $posts,
        ]);
    })->name('site.home');

});
