<?php

use function Livewire\Volt\{state, mount, with, usesPagination};
use App\Models\Site;

usesPagination();
state(['site', 'title' => '', 'content' => '']);

$createPost = function () {
    $this->site->posts()->create([
        'title' => $this->title,
        'content' => $this->content,
        'user_id' => Auth::user()->id,
    ]);

    $this->title = '';
    $this->content = '';
};

mount(function (Site $site) {
    $this->site = $site;
});

with(fn() => ['posts' => $this->site->posts()->with('user')->latest()->paginate(15)]);

?>

<div class="py-6 mx-auto max-w-3xl sm:px-6 lg:px-8">
    <header class="bg-white rounded-lg shadow">
        <div class="px-4 py-6 mx-auto max-w-3xl sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Manage Posts for {{ $site->name }}</h1>
            <p class="text-sm text-gray-500">
                <a href="{{ route('site.home', ['subdomain' => $site->subdomain]) }}"
                    class="text-blue-500 hover:underline">
                    {{ $site->subdomain }}.{{ parse_url(config('app.url'), PHP_URL_HOST) }}
                </a>
            </p>
        </div>
    </header>

    <main class="mt-6">
        <div class="px-4 py-6 sm:px-0">
            <div class="mb-8">
                <h2 class="mb-4 text-xl font-semibold">Create New Post</h2>
                <form wire:submit='createPost' class="p-6 space-y-4 bg-white shadow sm:rounded-lg">
                    <div>
                        <x-input-label for='title' :value="__('Title')" />
                        <x-text-input wire:model='title' id='title' class='block mt-1 w-full' type='text'
                            name='title' required autofocus />
                        <x-input-error :messages="$errors->get('title')" class='mt-2' />
                    </div>

                    <div>
                        <x-input-label for='content' :value="__('Content')" />
                        <textarea wire:model='content' id='content'
                            class='block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
                            name='content' rows="4" required></textarea>
                        <x-input-error :messages="$errors->get('content')" class='mt-2' />
                    </div>

                    <x-primary-button>{{ __('Create Post') }}</x-primary-button>
                </form>
            </div>

            <div>
                <h2 class="mb-4 text-xl font-semibold">Posts</h2>
                @forelse ($posts as $post)
                    <div class="overflow-hidden mb-4 bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $post->title }}</h3>
                                    <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $post->user->name }} ·
                                        {{ $post->created_at->format('M d, Y') }}</p>
                                </div>
                                <span
                                    class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                    {{ $post->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="px-4 py-5 border-t border-gray-200 sm:p-0">
                            <div class="sm:px-6 sm:py-5">
                                <p class="text-sm text-gray-900">{{ $post->content }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No posts yet.</p>
                @endforelse

                {{ $posts->links() }}
            </div>
        </div>
    </main>
</div>
