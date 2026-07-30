<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            Budget
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-budgets.alert-exceeded :exceeded-budgets="$exceededBudgets" />
            
            <x-budgets.budget-filter />

            @if ($budgets->isNotEmpty())
                <x-budgets.summary-cards 
                    :total-limit="$totalLimit" 
                    :total-spent="$totalSpent" 
                />

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2 space-y-3">
                        @foreach ($budgets as $budget)
                            <x-budgets.budget-card :budget="$budget" />
                        @endforeach
                    </div>

                    <x-budgets.allocation-chart 
                        :chart-data="$chartData" 
                        :total-limit="$totalLimit" 
                    />
                </div>
            @else
                <x-budgets.empty-state />
            @endif
        </div>
    </div>

    <livewire:budgets.budget-form />
    <x-modal-delete name="modal-delete-budget" title="Hapus Budget" description="Apakah Anda yakin ingin menghapus budget ini? Data budget yang dihapus tidak dapat dikembalikan." action="delete" />
</div>
