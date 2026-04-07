<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta name="google-site-verification" content="XDUltp0hE8n1iQSBjhJ339PM7d_XqldKzRBP33wC-m4" />
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
    <body class="font-sans text-slate-800 antialiased bg-slate-50 selection:bg-slate-800 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center p-4 sm:p-0">
            <div class="w-full sm:max-w-md px-8 py-10 bg-white border border-slate-200 shadow-2xl shadow-slate-200/50 overflow-hidden rounded-[2.5rem]">
                <!-- Integrated Logo -->
                <div class="flex justify-center mb-8">
                    <a href="/" wire:navigate class="transition hover:scale-105 active:scale-95">
                        <x-application-logo class="text-slate-900" />
                    </a>
                </div>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
