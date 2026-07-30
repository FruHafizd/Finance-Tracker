@props(['title' => null, 'description', 'size', 'ctaText'])

@if($size === 'lg')
<div class="bg-bg-sidebar rounded-3xl border border-dashed border-border p-16 text-center shadow-inner bg-bg/30">
    <div class="w-20 h-20 bg-bg-sidebar rounded-2xl shadow-sm border border-border flex items-center justify-center mx-auto mb-5 rotate-3 transition hover:rotate-0">
        <svg class="w-10 h-10 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </svg>
    </div>
    @if($title)
        <h3 class="text-text font-bold text-lg">{{ $title }}</h3>
    @endif
    <p class="text-text-muted text-sm mt-1 max-w-xs mx-auto">{{ $description }}</p>
    <button
        x-data x-on:click="$dispatch('open-account-form')"
        class="mt-6 px-6 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-xl font-bold text-sm shadow-md shadow-primary/10 ring-1 ring-inset ring-primary/20 transition-all">
        {{ $ctaText }}
    </button>
</div>
@elseif($size === 'sm')
<div class="px-4 sm:px-5 pb-4 sm:pb-5">
    <div class="bg-bg rounded-2xl border border-dashed border-border p-8 text-center">
        <p class="text-text-muted text-sm">{{ $description }}</p>
        <button
            x-data x-on:click="$dispatch('open-account-form')"
            class="mt-3 px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-xl font-bold text-xs shadow-md shadow-primary/10 ring-1 ring-inset ring-primary/20 transition-all">
            {{ $ctaText }}
        </button>
    </div>
</div>
@endif
