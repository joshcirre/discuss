<?php

namespace App\Jobs;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CreateTursoDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Site $site) {}

    public function handle(): void
    {
        $databaseName = $this->site->subdomain.'-tenant-'.Str::uuid();

        $response = Http::withToken(config('services.turso.api_key'))
            ->post('https://api.turso.tech/v1/organizations/'.config('services.turso.organization').'/databases', [
                'name' => $databaseName,
                'group' => 'discuss',
                'schema' => 'discuss-tenant',
            ]);

        if ($response->successful()) {
            $this->site->update(['database_name' => $databaseName]);
        } else {
            // Handle error
            throw new \Exception('Failed to create Turso database: '.$response->body());
        }
    }
}
