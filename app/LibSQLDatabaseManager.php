<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Contracts\TenantDatabaseManager;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class LibSQLDatabaseManager implements TenantDatabaseManager
{
    protected $apiToken;

    protected $organizationName;

    public function __construct()
    {
        $this->apiToken = config('services.turso.api_token');
        $this->organizationName = config('services.turso.organization_name');
    }

    public function createDatabase(TenantWithDatabase $tenant): bool
    {
        // The actual database creation is now handled in the CreateDatabase job
        // This method can be used for any additional setup if needed
        return true;
    }

    public function deleteDatabase(TenantWithDatabase $tenant): bool
    {
        $response = Http::withToken($this->apiToken)
            ->delete("https://api.turso.tech/v1/organizations/{$this->organizationName}/databases/{$tenant->database_name}");

        return $response->successful();
    }

    public function databaseExists(string $name): bool
    {
        $response = Http::withToken($this->apiToken)
            ->get("https://api.turso.tech/v1/organizations/{$this->organizationName}/databases/{$name}");

        return $response->successful();
    }

    public function makeConnectionConfig(array $baseConfig, string $databaseName): array
    {
        $baseConfig['url'] = Config::get('database.connections.libsql.url');
        $baseConfig['database'] = $databaseName;
        $baseConfig['token'] = Config::get('database.connections.libsql.token');

        return $baseConfig;
    }

    // These methods are not directly applicable to LibSQL/Turso, but we'll keep them for interface compatibility
    public function setConnection(string $connection): void
    {
        // Not applicable for LibSQL/Turso
    }
}
