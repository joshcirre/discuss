<?php

use function Livewire\Volt\{state, rules, layout};
use App\Models\Tenant;

state(['subdomain' => '']);
layout('layouts.guest');

rules(['subdomain' => 'required|alpha_dash|unique:domains,domain']);

$createTenant = function () {
    $this->validate();

    $tenant = Tenant::create();
    $tenant->domains()->create(['domain' => $this->subdomain]);

    // Redirect to the new tenant's login page
    $appUrl = preg_replace('#^https?://#', '', config('app.url'));
    return redirect('http://' . $this->subdomain . '.' . $appUrl . '/register');
};

?>
<div class="flex items-center justify-center w-full">
    <form wire:submit="createTenant" class="w-full max-w-md">
        <!-- Subdomain -->
        <div class="mb-4">
            <x-text-input wire:model="subdomain" id="subdomain" class="block w-full" type="text" name="subdomain" required
                autofocus placeholder="Enter your subdomain" />
            <x-input-error :messages="$errors->get('subdomain')" class="mt-2" />
        </div>

        <div class="flex items-center justify-center w-full mt-6">
            <x-primary-button class="justify-center w-full py-3 text-lg">
                {{ __('Create Your Discuss') }}
            </x-primary-button>
        </div>
    </form>
</div>
