{{-- ============================================================
    Pagination — Desktop & Mobile
    ============================================================ --}}

@if ($transactions->hasPages())
    <div class="bg-bg/50 px-5 py-4 border-t border-border sm:px-7 rounded-b-2xl">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">

            {{-- Info Text --}}
            <p class="text-[13px] font-medium text-text-muted order-2 sm:order-1">
                Menampilkan
                <span class="font-bold text-text">{{ $transactions->firstItem() }}</span> hingga <span class="font-bold text-text">{{ $transactions->lastItem() }}</span>
                dari <span class="font-bold text-text">{{ $transactions->total() }}</span> entri
            </p>

            {{-- Pagination Nav --}}
            <nav class="inline-flex rounded-xl shadow-sm ring-1 ring-inset ring-border overflow-hidden order-1 sm:order-2 bg-bg-sidebar" aria-label="Pagination">

                {{-- Previous --}}
                <button wire:click='previousPage' wire:loading.attr='disabled'
                    @if ($transactions->onFirstPage()) disabled @endif
                    class="relative inline-flex items-center px-3 py-2 text-sm border-r border-border transition-colors duration-150
                        {{ $transactions->onFirstPage()
                            ? 'bg-bg text-text-muted/40 cursor-not-allowed'
                            : 'bg-bg-sidebar text-text-muted hover:bg-bg hover:text-text-hover active:bg-border/50' }}">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- Desktop Page Numbers --}}
                <div class="hidden sm:flex">
                    @php
                        $currentPage = $transactions->currentPage();
                        $lastPage    = $transactions->lastPage();
                        $onEachSide  = 2;

                        $start = max(1, $currentPage - $onEachSide);
                        $end   = min($lastPage, $currentPage + $onEachSide);

                        $showStartDots = $start > 2;
                        $showEndDots   = $end < $lastPage - 1;
                    @endphp

                    {{-- First Page --}}
                    @if ($start > 1)
                        <button wire:click='gotoPage(1)'
                            class="relative inline-flex items-center px-4 py-2 text-[13px] font-bold border-r border-border transition-colors duration-150
                                {{ 1 === $currentPage
                                    ? 'bg-primary-light text-primary'
                                    : 'bg-bg-sidebar text-text-muted hover:bg-bg hover:text-text' }}">
                            1
                        </button>
                        @if ($showStartDots)
                            <span class="relative inline-flex items-center px-3 py-2 text-[13px] font-bold text-text-muted border-r border-border bg-bg/50">
                                ...
                            </span>
                        @endif
                    @endif

                    {{-- Middle Window --}}
                    @for ($page = $start; $page <= $end; $page++)
                        <button wire:click='gotoPage({{ $page }})'
                            class="relative inline-flex items-center px-4 py-2 text-[13px] font-bold border-r border-border transition-colors duration-150
                                {{ $page === $currentPage
                                    ? 'bg-primary-light text-primary shadow-[inset_0_-2px_0_0_theme(colors.primary.DEFAULT)]'
                                    : 'bg-bg-sidebar text-text-muted hover:bg-bg hover:text-text' }}">
                            {{ $page }}
                        </button>
                    @endfor

                    {{-- Last Page --}}
                    @if ($end < $lastPage)
                        @if ($showEndDots)
                            <span class="relative inline-flex items-center px-3 py-2 text-[13px] font-bold text-text-muted border-r border-border bg-bg/50">
                                ...
                            </span>
                        @endif
                        <button wire:click='gotoPage({{ $lastPage }})'
                            class="relative inline-flex items-center px-4 py-2 text-[13px] font-bold border-r border-border transition-colors duration-150
                                {{ $lastPage === $currentPage
                                    ? 'bg-primary-light text-primary shadow-[inset_0_-2px_0_0_theme(colors.primary.DEFAULT)]'
                                    : 'bg-bg-sidebar text-text-muted hover:bg-bg hover:text-text' }}">
                            {{ $lastPage }}
                        </button>
                    @endif
                </div>

                {{-- Current Page (Mobile) --}}
                <div class="sm:hidden inline-flex items-center px-4 py-2 bg-bg text-[13px] font-bold text-text-hover border-r border-border">
                    {{ $transactions->currentPage() }} / {{ $transactions->lastPage() }}
                </div>

                {{-- Next --}}
                <button wire:click='nextPage' wire:loading.attr='disabled'
                    @if (!$transactions->hasMorePages()) disabled @endif
                    class="relative inline-flex items-center px-3 py-2 text-sm transition-colors duration-150
                        {{ (!$transactions->hasMorePages())
                            ? 'bg-bg text-text-muted/40 cursor-not-allowed'
                            : 'bg-bg-sidebar text-text-muted hover:bg-bg hover:text-text-hover active:bg-border/50' }}">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </nav>

        </div>
    </div>
@endif
