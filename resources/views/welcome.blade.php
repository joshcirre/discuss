<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Let's Discuss</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center min-h-screen">
        <h1 class="mb-8 text-4xl font-bold text-gray-800 dark:text-white">Let's Discuss. 💬</h1>
        <div class="space-x-4">
            <livewire:create-tenant />
        </div>
    </div>
</body>

</html>
