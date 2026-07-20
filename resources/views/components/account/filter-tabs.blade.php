@props(['activeTab'])

<div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
    @foreach(['semua' => 'Semua', 'tabungan' => 'Tabungan', 'ewallet' => 'E-Wallet', 'tunai' => 'Tunai'] as $key => $label)
        <button
            wire:click="$set('activeTab', '{{ $key }}')"
            class="px-5 py-2 rounded-full text-sm font-medium transition-all duration-200 whitespace-nowrap
                {{ $activeTab === $key
                    ? 'bg-primary text-white shadow-sm ring-1 ring-primary hover:bg-primary-hover'
                    : 'bg-bg-sidebar text-text-muted border border-border hover:border-text-muted/30 hover:text-text hover:bg-bg' }}">
            {{ $label }}
        </button>
    @endforeach
</div>
