<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

{{-- ===== SEO dasar ===== --}}
<title>@yield('title', 'Finansiku — Kelola Keuangan Pribadi Lebih Cerdas & Simpel')</title>
<meta name="description" content="@yield('description', 'Finansiku mencatat setiap pemasukan dan pengeluaranmu otomatis, lengkap dengan budget, kalender tagihan, dan bot Telegram. Gratis, tanpa ribet.')">
<meta name="keywords" content="aplikasi keuangan pribadi, catat pengeluaran, budgeting, financial planner, Finansiku">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url()->current() }}">

{{-- ===== Open Graph / Twitter Card ===== --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="Finansiku">
<meta property="og:title" content="@yield('title', 'Finansiku — Kelola Keuangan Pribadi Lebih Cerdas & Simpel')">
<meta property="og:description" content="@yield('description', 'Catat pemasukan & pengeluaranmu otomatis, atur budget, dan pantau tagihan dalam satu dashboard. Gratis selamanya.')">
<meta property="og:image" content="{{ asset('images/og-cover.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', 'Finansiku')">
<meta name="twitter:description" content="@yield('description', 'Kelola keuangan pribadi lebih cerdas & simpel.')">
<meta name="twitter:image" content="{{ asset('images/og-cover.png') }}">

{{-- Favicon --}}
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="icon" href="{{ asset('images/logo-icon.svg') }}" type="image/svg+xml">

{{-- Structured data biar Google paham ini produk SaaS gratis --}}
@php
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'SoftwareApplication',
        'name' => 'Finansiku',
        'applicationCategory' => 'FinanceApplication',
        'operatingSystem' => 'Web',
        'offers' => [
            '@type' => 'Offer',
            'price' => '0',
            'priceCurrency' => 'IDR',
        ],
        'url' => url('/'),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('head')
</head>
<body class="font-sans text-text bg-bg antialiased">
    {{ $slot }}
    @stack('scripts')
</body>
</html>