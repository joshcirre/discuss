<?php

declare(strict_types=1);

use App\Models\Site;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyBySubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::domain('{subdomain}.discuss.test')->group(function () {
        Route::get('/', function ($subdomain) {
            $site = Site::where('subdomain', $subdomain)->firstOrFail();
            $posts = $site->posts()->with('user')->latest()->paginate(15);

            return view('public.sites.home', [
                'site' => $site,
                'posts' => $posts,
            ]);
        })->name('site.home');
    });
});
