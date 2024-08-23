<?php

use function Livewire\Volt\{state, mount, with, usesPagination};
use App\Models\Site;
use App\Models\Post;

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

$deletePost = function (Post $post) {
    $post->delete();
};

mount(function (Site $site) {
    $this->site = $site;
});

with(fn() => ['posts' => $this->site->posts()->with('user')->latest()->paginate(15)]);

?>

<div class="max-w-3xl py-6 mx-auto sm:px-6 lg:px-8">
    <header class="bg-white rounded-lg shadow">
        <div class="max-w-3xl px-4 py-6 mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Manage Posts for {{ $site->name }}</h1>
            <p class="text-sm text-gray-500">
                <a href="{{ route('site.home', ['site' => $site->subdomain]) }}"
                    class="mr-2 text-sm text-blue-600 hover:underline">View Site</a>
                @php
                    $baseUrl = config('app.url');
                    $baseUrl = parse_url($baseUrl, PHP_URL_HOST) ?: $baseUrl;
                    $baseUrl = rtrim($baseUrl, '/');
                @endphp
                {{ $site->subdomain }}.{{ tenant()->domains->first()->domain }}.{{ $baseUrl }}
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
                        <x-text-input wire:model='title' id='title' class='block w-full mt-1' type='text'
                            name='title' required autofocus />
                        <x-input-error :messages="$errors->get('title')" class='mt-2' />
                    </div>

                    <div>
                        <x-input-label for='content' :value="__('Content')" />
                        <textarea wire:model='content' id='content'
                            class='block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
                            name='content' rows="4" required></textarea>
                        <x-input-error :messages="$errors->get('content')" class='mt-2' />
                    </div>

                    <x-primary-button>{{ __('Create Post') }}</x-primary-button>
                </form>
            </div>

            <div>
                <h2 class="mb-4 text-xl font-semibold">Posts</h2>
                @forelse ($posts as $post)
                    <div class="mb-4 overflow-hidden bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $post->title }}</h3>
                                    <p class="max-w-2xl mt-1 text-sm text-gray-500">{{ $post->user->name }} ·
                                        {{ $post->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex px-2 mr-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                        {{ $post->created_at->diffForHumans() }}
                                    </span>
                                    <button wire:click="deletePost({{ $post->id }})"
                                        class="text-red-600 hover:text-red-900"
                                        wire:confirm="Are you sure you want to delete this post?">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
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
