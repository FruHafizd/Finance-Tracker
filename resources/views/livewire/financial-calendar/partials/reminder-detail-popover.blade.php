@php
    $reminder = \App\Models\FinancialReminder::find($selectedReminderId);
@endphp

@if($reminder)
    @php
        $c = $this->getCategoryColors($reminder->category);
    @endphp
    <template x-teleport="body">
        {{-- Wrapper: reads popoverTop/popoverLeft from parent (calendar-grid) scope via x-teleport scope chain --}}
        <div x-data="{
                 isMobile: window.innerWidth < 640,
                 pTop: 0,
                 pLeft: 0,
                 init() {
                     this.isMobile = window.innerWidth < 640;
                     this.calcPosition();
                 },
                 calcPosition() {
                     const popW = 352;
                     const popH = 260;
                     const pad = 16;
                     let t = popoverTop || 0;
                     let l = popoverLeft || 0;

                     if (l + popW > window.innerWidth - pad) {
                         l = window.innerWidth - popW - pad;
                     }
                     if (l < pad) {
                         l = pad;
                     }
                     if (t + popH > window.innerHeight - pad) {
                         t = t - popH - 8;
                     }
                     if (t < pad) {
                         t = pad;
                     }
                     this.pTop = t;
                     this.pLeft = l;
                 }
             }"
             class="contents">

            <!-- Invisible backdrop for outside click to close (fixed fullscreen) -->
            <div class="fixed inset-0 z-[150]" wire:click="$set('selectedReminderId', null)"></div>

            <!-- Popover Card: positioned via fixed + computed top/left -->
            <div class="fixed z-[151] bg-bg-sidebar rounded-2xl shadow-xl border border-border p-5 w-full max-w-[22rem] flex flex-col gap-3 transform transition-all"
                 :class="isMobile ? 'top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2' : ''"
                 :style="!isMobile ? 'top:' + pTop + 'px; left:' + pLeft + 'px;' : ''">
                <!-- Action Buttons & Close -->
                <div class="absolute top-3 right-3 flex items-center gap-1">
                    <!-- Delete -->
                    <button type="button" wire:click="confirmDelete({{ $reminder->id }})" class="w-10 h-10 flex items-center justify-center text-text-muted hover:text-danger hover:bg-danger/10 rounded-lg transition-colors" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    <!-- Close -->
                    <button type="button" wire:click="$set('selectedReminderId', null)" class="w-10 h-10 flex items-center justify-center text-text-muted hover:text-text hover:bg-bg rounded-lg transition-colors ml-1" title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Header: Title and Date -->
                <div class="pr-20">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-2.5 h-2.5 rounded-full {{ $c['dot'] }}"></div>
                        <h4 class="text-base font-bold text-text leading-tight">{{ e($reminder->description) }}</h4>
                    </div>
                    <div class="text-xs text-text-muted ml-[18px]">
                        {{ \Carbon\Carbon::createFromDate($reminder->year, $reminder->month, $reminder->day)->translatedFormat('l, d F Y') }}
                    </div>
                </div>

                <!-- Details -->
                <div class="mt-2 space-y-2 ml-[18px]">
                    <div class="flex items-center gap-2 text-xs text-text-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span>Diingatkan {{ $reminder->remind_before == 0 ? 'pada hari H' : 'H-' . $reminder->remind_before }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-text-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        <span>Kategori: {{ $reminder->category }}</span>
                    </div>
                </div>

                <!-- Amount -->
                <div class="mt-4 pt-3 border-t border-border flex justify-between items-end">
                    <span class="text-xs text-text-muted font-medium mb-1">Nominal</span>
                    <span class="text-xl font-bold text-text">Rp {{ $reminder->amount_formatted }}</span>
                </div>

                {{-- Tombol Tambah ke Transaksi --}}
                <div class="mt-3 pt-3 border-t border-border">
                    <button type="button"
                        wire:click="convertToTransaction({{ $reminder->id }})"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 
                               bg-primary hover:opacity-90 text-white text-sm font-semibold 
                               rounded-xl transition-all shadow-sm hover:shadow-md"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah ke Transaksi
                    </button>
                </div>
            </div>
        </div>
    </template>
@endif
