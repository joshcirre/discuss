<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\VoltServiceProvider::class,
    App\Providers\TenancyServiceProvider::class,
    Turso\Driver\Laravel\LibSQLDriverServiceProvider::class,
];
