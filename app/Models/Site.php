<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Models\Concerns\ImplementsTenant;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Spatie\Multitenancy\Models\Tenant;

class Site extends Tenant
{
    use HasFactory;
    use ImplementsTenant;
    use UsesLandlordConnection;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    protected static function booted()
    {
        static::creating(function (Site $site) {
            $site->database = 'tenant_'.Str::uuid().'.sqlite';
        });

        static::created(function (Site $site) {
            $site->createDatabase();
            $site->makeCurrent();
            $site->migrateTenant();
        });
    }

    public function createDatabase()
    {
        $tenantDatabasePath = storage_path('app/tenants');
        if (! file_exists($tenantDatabasePath)) {
            mkdir($tenantDatabasePath, 0755, true);
        }
        $databaseFile = $tenantDatabasePath.'/'.$this->database;
        touch($databaseFile);

        config(['database.connections.tenant.database' => $databaseFile]);
    }

    public function migrateTenant()
    {
        $this->makeCurrent();

        $tenantDatabasePath = storage_path('app/tenants/'.$this->database);
        config(['database.connections.tenant.database' => $tenantDatabasePath]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        Log::info("Migration output for tenant {$this->id}: ".Artisan::output());
    }
}
