{{-- ============================================================
    Transactions Index — Orchestrator
    Partial files di: partials/_summary-cards, _filter-section,
    _table-desktop, _table-mobile, _pagination
    ============================================================ --}}

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            Transaksi
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">

        {{-- Summary Cards --}}
        @include('livewire.transactions.partials._summary-cards')

        {{-- Main Table Section --}}
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-bg-sidebar overflow-hidden shadow-sm rounded-2xl ring-1 ring-inset ring-border">

                {{-- Filter Section --}}
                @include('livewire.transactions.partials._filter-section')

                {{-- Desktop Table --}}
                @include('livewire.transactions.partials._table-desktop')

                {{-- Mobile Card View --}}
                @include('livewire.transactions.partials._table-mobile')

                {{-- Pagination --}}
                @include('livewire.transactions.partials._pagination')

            </div>
        </div>

        {{-- Modals --}}
        <livewire:transactions.transaction-form />
        <x-modal-delete
            name="modal-delete-transaksi"
            title="Hapus Transaksi"
            description="Apakah Anda yakin ingin menghapus catatan transaksi ini? Semua data terkait transaksi ini akan dihapus permanen dan tidak bisa dikembalikan."
            action="delete"
        />
        <livewire:transactions.category />
        <livewire:transactions.export />

    </div>
</div>
