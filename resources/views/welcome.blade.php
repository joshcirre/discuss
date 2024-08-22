<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Discuss</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="flex flex-col justify-center items-center min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="text-center">
            <h1 class="mb-6 text-5xl font-bold text-gray-900 dark:text-white">Welcome to Discuss</h1>


            <div class="mt-10">
                <a href="{{ route('create-discuss') }}" wire:navigate
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Create
                    Your Discuss</a>
            </div>
        </div>
    </div>
</body>

</html>
