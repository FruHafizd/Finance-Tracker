<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Legal' }} - {{ config('app.name', 'Finansiku') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased selection:bg-slate-900 selection:text-white">

    <!-- Navbar Minimalis -->
    <nav class="sticky top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <a href="/" class="flex items-center gap-2 transition hover:opacity-80">
                        <div class="bg-slate-900 p-1.5 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-bold tracking-tight text-slate-900">{{ config('app.name', 'Finansiku') }}</span>
                    </a>
                </div>
                <div>
                    <a href="/" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-16 md:py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="p-8 md:p-16">
                    {{ $slot }}
                </div>
            </div>
            
            <div class="mt-12 text-center">
                <p class="text-sm text-slate-400">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Semua Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </main>

</body>
</html>
