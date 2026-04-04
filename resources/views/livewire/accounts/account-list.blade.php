<div class="max-w-4xl mx-auto px-4 py-8 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Rekening</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola semua rekening kamu</p>
        </div>
        <button
            x-data
            x-on:click="$dispatch('open-account-form')"
            class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Rekening
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="col-span-2 sm:col-span-1 bg-indigo-600 rounded-2xl p-4 text-white">
            <p class="text-xs font-medium text-indigo-200">Total Saldo</p>
            <p class="text-xl font-semibold mt-1">
                Rp {{ number_format($this->summary['total'], 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-500">Tabungan</p>
            <p class="text-base font-semibold text-gray-800 mt-1">
                Rp {{ number_format($this->summary['tabungan'], 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-500">E-Wallet</p>
            <p class="text-base font-semibold text-gray-800 mt-1">
                Rp {{ number_format($this->summary['ewallet'], 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-500">Tunai</p>
            <p class="text-base font-semibold text-gray-800 mt-1">
                Rp {{ number_format($this->summary['tunai'], 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- Filter Tab --}}
    <div class="flex gap-2">
        @foreach(['semua' => 'Semua', 'tabungan' => 'Tabungan', 'ewallet' => 'E-Wallet', 'tunai' => 'Tunai'] as $key => $label)
            <button
                wire:click="$set('activeTab', '{{ $key }}')"
                class="px-4 py-1.5 rounded-full text-sm font-medium transition
                    {{ $activeTab === $key
                        ? 'bg-indigo-600 text-white'
                        : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Account List --}}
    <div class="space-y-3">
        @forelse($this->accounts as $account)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">

                {{-- Icon --}}
                <div
                    class="w-11 h-11 rounded-xl flex items-center justify-center text-sm font-semibold flex-shrink-0"
                    style="background-color: {{ $account->color ?? '#6366f1' }}22; color: {{ $account->color ?? '#6366f1' }}">
                    {{ strtoupper(substr($account->provider ?? $account->name, 0, 2)) }}
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="font-medium text-gray-800 text-sm">{{ $account->name }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $account->type === 'tabungan' ? 'bg-blue-50 text-blue-600' : '' }}
                            {{ $account->type === 'ewallet'  ? 'bg-emerald-50 text-emerald-600' : '' }}
                            {{ $account->type === 'tunai'    ? 'bg-amber-50 text-amber-600' : '' }}">
                            {{ ucfirst($account->type) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $account->provider ?? 'Tunai' }}</p>
                </div>

                {{-- Saldo --}}
                <div class="text-right flex-shrink-0">
                    <p class="font-semibold text-gray-800 text-sm">
                        Rp {{ number_format($account->balance, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">saldo</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-1 flex-shrink-0" x-data>
                    <button
                        x-on:click="$dispatch('open-account-form', { id: {{ $account->id }} })"
                        class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button
                        wire:click="confirmDelete({{ $account->id }})"
                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-12 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <p class="text-gray-500 text-sm font-medium">Belum ada rekening</p>
                <p class="text-gray-400 text-xs mt-1">Tambahkan rekening pertama kamu</p>
            </div>
        @endforelse
    </div>

    {{-- Modal Form --}}
    <livewire:accounts.account-form />

    <x-modal-delete
        name="modal-delete-rekening"
        title="Hapus Rekening"
        description="Apakah Anda yakin ingin menghapus rekening ini? Rekening hanya dapat dihapus jika tidak memiliki riwayat transaksi."
        action="delete"
    />

</div>