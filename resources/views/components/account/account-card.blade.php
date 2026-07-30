@props(['account', 'percentage', 'isLowBalance'])

<div class="bg-bg rounded-2xl border {{ $isLowBalance ? 'border-danger ring-1 ring-danger/10' : 'border-border' }} shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
    <div class="p-4 sm:p-5 flex flex-col">
        {{-- Baris Label Kecil di Atas --}}
        <div class="text-[10px] font-bold text-text-muted tracking-wider uppercase mb-3 pb-2 border-b border-border/70 flex justify-between items-center">
            <span>
                {{ $account->transactions_count_this_month }} Transaksi Bulan Ini · Terakhir {{ $account->last_transaction_time ? $account->last_transaction_time->diffForHumans() : 'Belum Ada Transaksi' }}
            </span>
            {{-- Kalo Saldo Menipis taruh badge juga --}}
            @if($isLowBalance)
                <span class="text-[9px] uppercase tracking-wider px-2 py-0.5 rounded-md font-bold bg-danger/10 text-danger border border-danger/20 animate-pulse flex items-center gap-1">
                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Saldo Menipis
                </span>
            @endif
        </div>

        {{-- Main Info Row --}}
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                {{-- Icon Avatar --}}
                <div
                    class="w-12 h-12 rounded-2xl flex items-center justify-center text-sm font-bold flex-shrink-0"
                    style="background-color: {{ $account->color ?? '#475569' }}15; color: {{ $account->color ?? '#475569' }}">
                    {{ strtoupper(substr($account->provider ?? $account->name, 0, 2)) }}
                </div>

                {{-- Info Text --}}
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="font-bold text-text text-base leading-tight truncate">{{ $account->name }}</p>
                        
                        {{-- Type Badge with Icon --}}
                        <span class="text-[9px] uppercase tracking-wider px-2 py-0.5 rounded-md font-bold bg-bg text-text-muted border border-border flex items-center gap-1">
                            <x-account.type-icon :type="$account->type" class="w-3.5 h-3.5" />
                            {{ $account->type }}
                        </span>
                    </div>
                    <p class="text-xs text-text-muted mt-1 font-medium">{{ $account->provider ?? 'Tunai' }}</p>
                </div>
            </div>

            {{-- Saldo & Delta & Actions --}}
            <div class="flex items-center gap-4">
                <div class="text-right flex-shrink-0">
                    <p class="font-bold {{ $isLowBalance ? 'text-danger' : 'text-text' }} text-lg leading-tight">
                        Rp {{ number_format($account->balance, 0, ',', '.') }}
                    </p>
                    
                    {{-- Delta change --}}
                    <x-account.change-indicator :amount="$account->monthly_change" variant="row" />
                </div>

                {{-- Actions Desktop --}}
                <div class="hidden sm:flex items-center gap-1 ml-4" x-data>
                    <button
                        x-on:click="$dispatch('open-transfer', { fromAccountId: {{ $account->id }} })"
                        class="p-2.5 text-text-muted hover:text-text hover:bg-bg rounded-xl transition"
                        title="Transfer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </button>
                    <button
                        x-on:click="$dispatch('open-account-form', { id: {{ $account->id }} })"
                        class="p-2.5 text-text-muted hover:text-text hover:bg-bg rounded-xl transition"
                        title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button
                        wire:click="confirmDelete({{ $account->id }})"
                        class="p-2.5 text-text-muted hover:text-danger hover:bg-danger/10 rounded-xl transition"
                        title="Hapus">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Progress Bar --}}
        @if($percentage > 0)
            <div class="mt-4 flex items-center justify-between gap-3">
                <div class="flex-1 bg-bg rounded-full h-2 overflow-hidden">
                    <div
                        class="h-full rounded-full transition-all duration-1000 ease-out"
                        style="width: {{ min($percentage, 100) }}%; background-color: {{ $account->color ?? '#475569' }}">
                    </div>
                </div>
                <span class="text-[11px] font-bold text-text-muted whitespace-nowrap">
                    {{ $percentage }}%
                </span>
            </div>
        @endif

        {{-- Mobile Actions --}}
        <div class="flex sm:hidden items-center gap-2 mt-4 pt-4 border-t border-border/50" x-data>
            <button
                x-on:click="$dispatch('open-transfer', { fromAccountId: {{ $account->id }} })"
                class="flex-1 flex items-center justify-center gap-2 py-3 rounded-2xl bg-primary hover:bg-primary-hover text-white text-[11px] font-bold shadow-md ring-1 ring-inset ring-primary/20 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                Transfer
            </button>
            <button
                x-on:click="$dispatch('open-account-form', { id: {{ $account->id }} })"
                class="flex-1 flex items-center justify-center gap-2 py-3 rounded-2xl bg-bg-sidebar text-text text-[11px] font-bold border border-border hover:bg-bg active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </button>
            <button
                wire:click="confirmDelete({{ $account->id }})"
                class="w-12 flex items-center justify-center py-3 rounded-2xl bg-danger/10 text-danger border border-danger/20 hover:bg-danger/20 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    </div>
</div>
