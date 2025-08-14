<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body  
      style="background: linear-gradient(170deg, rgba(116,163,255,1), rgba(255,255,255,0.1)); backdrop-filter: blur(1px);">


        <div class="min-h-screen mt-2 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-transparent">
            <div>
                <a href="/" wire:navigate>
                    <img src="{{ asset('storage/logo.png') }}" 
                        alt="Logo" 
                        class="w-40 h-40 mb-10 rounded-full shadow-lg border-4 border-white object-cover">
                </a>
            </div>

            <div class="w-128 sm:max-w-7x1 mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
