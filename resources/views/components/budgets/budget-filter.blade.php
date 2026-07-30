<div class="flex items-center justify-between gap-4 flex-wrap">
    <div class="flex items-center gap-2">
        <div class="flex items-center gap-1 bg-bg-sidebar border border-border rounded-xl px-1 py-1 shadow-sm">
            <svg class="w-4 h-4 text-text-muted ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <select wire:model.live="month" class="border-0 bg-transparent text-sm text-text font-medium focus:outline-none focus:ring-0 pr-2 py-1 cursor-pointer">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-1 bg-bg-sidebar border border-border rounded-xl px-1 py-1 shadow-sm">
            <select wire:model.live="year" class="border-0 bg-bg-sidebar text-sm text-text font-medium focus:outline-none focus:ring-0 px-2 py-1 pr-7 cursor-pointer">
                @foreach (range((int) now()->format('Y') - 1, (int) now()->format('Y') + 1) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <button wire:click="$dispatch('open-create-budget')" class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-white text-sm font-medium rounded-xl hover:bg-primary-hover active:scale-95 transition-all duration-150 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Budget
    </button>
</div>
