<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
       <div class="min-h-screen bg-gray-100 flex flex-col">
            <livewire:layout.navigation />

            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-1">
                {{ $slot }}
            </main>

            <x-footer />
        </div>

        {{-- Budget Alert Toast --}}
        <div
            x-data="{
                toasts: [],
                add(toast) {
                    const id = Date.now();
                    this.toasts.push({ ...toast, id, show: true });
                    setTimeout(() => this.remove(id), 5000);
                },
                remove(id) {
                    const toast = this.toasts.find(t => t.id === id);
                    if (toast) toast.show = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 300);
                }
            }"
            x-on:budget-alert.window="add($event.detail)"

            class="fixed bottom-5 right-5 z-50 flex flex-col gap-3 w-80"
        >
            <template x-for="toast in toasts" :key="toast.id">
                <div
                    x-show="toast.show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4"
                    :class="toast.type === 'danger'
                        ? 'bg-red-50 border border-red-200'
                        : 'bg-amber-50 border border-amber-200'"
                    class="rounded-xl p-4 shadow-lg">

                    <div class="flex items-start gap-3">
                        {{-- Icon --}}
                        <div
                            :class="toast.type === 'danger' ? 'bg-red-100' : 'bg-amber-100'"
                            class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                            <span x-text="toast.type === 'danger' ? '🚨' : '⚠️'" class="text-sm"></span>
                        </div>

                        {{-- Konten --}}
                        <div class="flex-1 min-w-0">
                            <p
                                :class="toast.type === 'danger' ? 'text-red-800' : 'text-amber-800'"
                                class="text-sm font-semibold"
                                x-text="toast.title">
                            </p>
                            <p
                                :class="toast.type === 'danger' ? 'text-red-600' : 'text-amber-600'"
                                class="text-xs mt-0.5 leading-relaxed"
                                x-text="toast.message">
                            </p>
                        </div>

                        {{-- Tombol tutup --}}
                        <button
                            x-on:click="remove(toast.id)"
                            :class="toast.type === 'danger'
                                ? 'text-red-400 hover:text-red-600 hover:bg-red-100'
                                : 'text-amber-400 hover:text-amber-600 hover:bg-amber-100'"
                            class="flex-shrink-0 rounded-lg p-1 transition-colors duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Progress bar countdown --}}
                    <div
                        :class="toast.type === 'danger' ? 'bg-red-200' : 'bg-amber-200'"
                        class="mt-3 w-full rounded-full h-1 overflow-hidden">
                        <div
                            :class="toast.type === 'danger' ? 'bg-red-400' : 'bg-amber-400'"
                            class="h-1 rounded-full"
                            style="animation: shrink 5s linear forwards">
                        </div>
                    </div>

                </div>
            </template>
        </div>

        <style>
            @keyframes shrink {
                from { width: 100%; }
                to   { width: 0%; }
            }
        </style>

        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @stack('scripts')
    </body>
</html>