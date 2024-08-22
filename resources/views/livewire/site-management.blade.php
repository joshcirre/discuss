<?php

use function Livewire\Volt\{state, with};
use App\Models\Site;
use App\Jobs\CreateTursoDatabase;

state(['name' => '', 'subdomain' => '']);

$createSite = function () {
    $site = Auth::user()
        ->sites()
        ->create([
            'name' => $this->name,
            'subdomain' => $this->subdomain,
        ]);

    CreateTursoDatabase::dispatch($site);

    $this->name = '';
    $this->subdomain = '';
};

$deleteSite = function (int $id) {
    $site = Site::find($id);
    $site->delete();
};

with(fn() => ['sites' => Auth::user()->sites]);

?>

<div class="py-6 mx-auto max-w-3xl sm:px-6 lg:px-8">
    <header class="bg-white rounded-lg shadow">
        <div class="px-4 py-6 mx-auto max-w-3xl sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Manage Sites</h1>
        </div>
    </header>

    <main class="mt-6">
        <div class="px-4 py-6 sm:px-0">
            <div x-data="{
                name: @entangle('name'),
                subdomain: @entangle('subdomain'),
                generateSubdomain() {
                    this.subdomain = this.name.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
                }
            }">
                <div class="mb-8">
                    <h2 class="mb-4 text-xl font-semibold">Create New Site</h2>
                    <form wire:submit='createSite' class="p-6 space-y-4 bg-white shadow sm:rounded-lg">
                        <div>
                            <x-input-label for='name' :value="__('Site Name')" />
                            <x-text-input x-model="name" @input="generateSubdomain" id='name'
                                class='block mt-1 w-full' type='text' name='name' required autofocus />
                            <x-input-error :messages="$errors->get('name')" class='mt-2' />
                        </div>

                        <div>
                            <x-input-label for='subdomain' :value="__('Subdomain')" />
                            <x-text-input x-model="subdomain" id='subdomain' class='block mt-1 w-full' type='text'
                                name='subdomain' required />
                            <x-input-error :messages="$errors->get('subdomain')" class='mt-2' />
                        </div>

                        <x-primary-button>{{ __('Create Site') }}</x-primary-button>
                    </form>
                </div>
            </div>

            <div>
                <h2 class="mb-4 text-xl font-semibold">Your Sites</h2>
                @forelse ($sites as $site)
                    <div class="overflow-hidden mb-4 bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $site->name }}</h3>
                                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                        {{ $site->subdomain }}.{{ parse_url(config('app.url'), PHP_URL_HOST) }}
                                    </p>
                                    <div class="mt-2">
                                        <a href="{{ route('site.home', ['subdomain' => $site->subdomain]) }}"
                                            class="mr-4 text-sm text-blue-600 hover:underline">View Site</a>
                                        <a href="{{ route('sites.manage', $site) }}"
                                            class="text-sm text-blue-600 hover:underline">Manage Posts</a>
                                    </div>
                                </div>
                                <button wire:click='deleteSite({{ $site->id }})'
                                    wire:confirm="Are you sure you want to delete this site?"
                                    class="px-4 py-2 text-sm font-medium text-red-600 bg-red-100 rounded-md hover:bg-red-200">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No sites yet.</p>
                @endforelse
            </div>
        </div>
    </main>
</div>
