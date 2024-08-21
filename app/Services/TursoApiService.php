<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TursoApiService
{
    protected $baseUrl = 'https://api.turso.tech/v1';

    protected $token;

    protected $organizationName;

    public function __construct()
    {
        $this->token = config('services.turso.token');
        $this->organizationName = config('services.turso.organization');
    }

    public function createDatabase($name)
    {
        $response = Http::withToken($this->token)->post("{$this->baseUrl}/organizations/{$this->organizationName}/databases", [
            'name' => $name,
        ]);

        return $response->json();
    }
}
