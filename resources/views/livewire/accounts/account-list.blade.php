<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            Rekening
        </h2>
    </x-slot>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        {{-- Total Saldo --}}
        <x-account.balance-hero-card 
            :total="$this->summary['total']" 
            :net-change="$this->summary['netChange'] ?? 0" 
        />

        {{-- Card per Tipe --}}
        @foreach(['tabungan' => 'Tabungan', 'ewallet' => 'E-Wallet', 'tunai' => 'Tunai'] as $typeKey => $typeLabel)
            <x-account.type-summary-card 
                :type-key="$typeKey" 
                :type-label="$typeLabel" 
                :amount="$this->summary[$typeKey]" 
                :change="$this->summary[$typeKey . '_change'] ?? 0" 
                :account-names="$this->accountsByType[$typeKey]" 
            />
        @endforeach
    </div>

    <x-account.toolbar :sort-by="$sortBy" :sort-dir="$sortDir" />

    <x-account.filter-tabs :active-tab="$activeTab" />

    {{-- Account List - Accordion Groups --}}
    <div class="space-y-4">
        @php
            $groupedAccounts = $this->accountsGroupedByType;
            $allAccountsEmpty = true;
            foreach ($groupedAccounts as $group) {
                if ($group['count'] > 0) {
                    $allAccountsEmpty = false;
                    break;
                }
            }
        @endphp

        @if($allAccountsEmpty)
            {{-- Empty state when no accounts at all --}}
            <x-account.empty-state 
                size="lg" 
                title="Belum ada rekening" 
                description="Mulai kelola keuanganmu dengan menambahkan rekening pertama hari ini." 
                cta-text="Buat Rekening Sekarang" 
            />
        @else
            {{-- Accordion Groups --}}
            @foreach(['tabungan' => 'Tabungan', 'ewallet' => 'E-Wallet', 'tunai' => 'Tunai'] as $typeKey => $typeLabel)
                @php
                    $group = $groupedAccounts[$typeKey];
                    $isExpanded = $this->expandedGroups[$typeKey] ?? false;
                    $showGroup = $this->activeTab === 'semua' || $this->activeTab === $typeKey;
                @endphp

                @if($showGroup)
                    <x-account.accordion-group 
                        :type-key="$typeKey" 
                        :type-label="$typeLabel" 
                        :count="$group['count']" 
                        :total-balance="$group['total_balance']" 
                        :expanded="$isExpanded">
                        
                        @if($group['count'] > 0)
                            <div class="px-4 sm:px-5 pb-4 sm:pb-5 space-y-3">
                                @foreach($group['accounts'] as $account)
                                    @php
                                        $percentage = $this->accountPercentages[$account->id] ?? 0;
                                        $isLowBalance = $account->balance <= \App\Livewire\Accounts\AccountList::LOW_BALANCE_THRESHOLD;
                                    @endphp

                                    <x-account.account-card 
                                        :account="$account" 
                                        :percentage="$percentage" 
                                        :is-low-balance="$isLowBalance" 
                                    />
                                @endforeach
                            </div>
                        @else
                            {{-- Empty state for this category --}}
                            <x-account.empty-state 
                                size="sm" 
                                description="Belum ada rekening di kategori ini" 
                                cta-text="Tambah Rekening {{ $typeLabel }}" 
                            />
                        @endif
                    </x-account.accordion-group>
                @endif
            @endforeach
        @endif
    </div>

    {{-- Modal Form --}}
    <livewire:accounts.account-form />

    <x-modal-delete
        name="modal-delete-rekening"
        title="Hapus Rekening"
        description="Apakah Anda yakin ingin menghapus rekening ini? Rekening hanya dapat dihapus jika tidak memiliki riwayat transaksi apa pun."
        action="delete"
    />

    {{-- Form Transaksi (untuk Quick Transfer) --}}
    <livewire:transactions.transaction-form />
    <livewire:transactions.category />

</div>