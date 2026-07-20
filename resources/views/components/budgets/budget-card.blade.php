@props(['budget'])

<div class="rounded-xl p-5 border border-border bg-bg-sidebar shadow-sm transition-all duration-200 hover:shadow-md">
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center gap-2.5 flex-wrap">
            <span class="w-3.5 h-3.5 rounded-full flex-shrink-0 ring-2 ring-bg-sidebar shadow-sm" style="background-color: {{ $budget->category->color ?? '#6366f1' }}"></span>
            <span class="font-semibold text-text text-sm">{{ $budget->category->name }}</span>
            @if ($budget->isWarning)
                <span class="inline-flex items-center gap-1 text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">Hampir habis</span>
            @elseif ($budget->isDanger)
                <span class="inline-flex items-center gap-1 text-xs bg-danger/10 text-danger px-2 py-0.5 rounded-full font-medium">Melebihi budget</span>
            @endif
        </div>

        <div class="flex items-center gap-1 flex-shrink-0">
            <button x-data x-on:click="$dispatch('edit-budget', { id: {{ $budget->id }} });" class="p-1.5 text-text-muted hover:text-text-hover hover:bg-bg rounded-lg transition-colors duration-150" title="Edit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </button>
            <button x-data x-on:click="$wire.confirmDelete({{ $budget->id }})" class="p-1.5 text-text-muted hover:text-danger hover:bg-danger/10 rounded-lg transition-colors duration-150" title="Hapus">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </button>
        </div>
    </div>

    <div class="flex justify-between items-baseline mb-2">
        <span class="text-xl font-bold text-text">Rp {{ number_format($budget->spent, 0, ',', '.') }}</span>
        <span class="text-xs text-text-muted">dari Rp {{ number_format($budget->limit_amount, 0, ',', '.') }}</span>
    </div>
    <div class="w-full bg-bg rounded-full h-2 overflow-hidden">
        <div class="{{ $budget->barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ min($budget->percentage, 100) }}%"></div>
    </div>
    <div class="flex justify-between items-center mt-1.5">
        <span class="text-xs {{ $budget->isDanger ? 'text-danger font-medium' : ($budget->isWarning ? 'text-amber-600 font-medium' : 'text-text-muted') }}">{{ $budget->percentage }}% terpakai</span>
        <span class="text-xs text-text-muted">Sisa Rp {{ number_format(max($budget->limit_amount - $budget->spent, 0), 0, ',', '.') }}</span>
    </div>
</div>
