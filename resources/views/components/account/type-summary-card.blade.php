@props(['typeKey', 'typeLabel', 'amount', 'change', 'accountNames'])

<div class="bg-bg-sidebar rounded-2xl p-4 sm:p-5 border border-border shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col justify-between">
    <div>
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-text-muted font-medium">{{ $typeLabel }}</p>
                <p class="text-base font-bold text-text mt-1">
                    Rp {{ number_format($amount, 0, ',', '.') }}
                </p>
            </div>
            <div class="text-primary bg-primary-light p-1.5 rounded-lg flex-shrink-0">
                <x-account.type-icon :type="$typeKey" class="w-4 h-4" />
            </div>
        </div>

        <x-account.change-indicator :amount="$change" variant="card" />
    </div>

    {{-- Row Info Jumlah & Nama Rekening --}}
    <div class="mt-3 pt-2 border-t border-border">
        <p class="text-[10px] text-text-muted font-medium truncate">
            {{ $accountNames }}
        </p>
    </div>
</div>
