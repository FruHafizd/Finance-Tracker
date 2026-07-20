@props(['typeKey', 'typeLabel', 'count', 'totalBalance', 'expanded'])

<div class="bg-bg-sidebar rounded-2xl border border-border shadow-sm overflow-hidden">
    {{-- Accordion Header --}}
    <button
        wire:click="toggleGroup('{{ $typeKey }}')"
        class="w-full p-4 sm:p-5 flex items-center gap-3 sm:gap-4 min-h-[44px] hover:bg-bg/50 transition-colors duration-200 text-left">
        {{-- Chevron Icon --}}
        <div class="flex-shrink-0 text-text-muted transition-transform duration-200 {{ $expanded ? 'rotate-90' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>

        {{-- Category Icon --}}
        <div class="text-primary bg-primary-light p-1.5 rounded-lg flex-shrink-0">
            <x-account.type-icon :type="$typeKey" class="w-4 h-4" />
        </div>

        {{-- Category Name & Count --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <p class="font-bold text-text text-sm sm:text-base truncate">{{ $typeLabel }}</p>
                <span class="text-[11px] text-text-muted font-medium">({{ $count }} rekening)</span>
            </div>
        </div>

        {{-- Total Balance --}}
        <div class="text-right flex-shrink-0">
            <p class="font-bold text-text text-sm sm:text-base">
                Rp {{ number_format($totalBalance, 0, ',', '.') }}
            </p>
        </div>
    </button>

    {{-- Accordion Content --}}
    <div class="{{ $expanded ? '' : 'hidden' }}">
        {{ $slot }}
    </div>
</div>
