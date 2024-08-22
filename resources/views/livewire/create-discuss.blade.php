<?php

use function Livewire\Volt\{state, rules, layout};
use App\Models\Tenant;

layout('layouts.guest');

state(['subdomain' => '']);

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
<div>
    <form wire:submit="createTenant">
        <!-- Subdomain -->
        <div>
            <x-input-label for="subdomain" :value="__('Subdomain')" />
            <x-text-input wire:model="subdomain" id="subdomain" class="block mt-1 w-full" type="text" name="subdomain"
                required autofocus />
            <x-input-error :messages="$errors->get('subdomain')" class="mt-2" />
        </div>

        <div class="flex justify-end items-center mt-4">
            <x-primary-button class="ms-4">
                {{ __('Create Discuss') }}
            </x-primary-button>
        </div>
    </form>
</div>
