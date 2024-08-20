<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $site->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <header class="bg-white shadow">
        <div class="px-4 py-6 mx-auto max-w-3xl sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ $site->name }}</h1>
            <p class="text-sm text-gray-500">{{ $site->subdomain }}.{{ parse_url(config('app.url'), PHP_URL_HOST) }}</p>
        </div>
    </header>

    <main class="py-6 mx-auto max-w-3xl sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
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
        </div>
        {{ $posts->links() }}
    </main>
</body>

</html>
