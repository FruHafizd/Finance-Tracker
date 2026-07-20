@props(['exceededBudgets'])

@if ($exceededBudgets->isNotEmpty())
    <div class="rounded-xl border border-danger/20 bg-danger/10 p-4">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-danger/10 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-sm text-danger font-bold">!</span>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-danger">
                    {{ $exceededBudgets->count() }} kategori sudah melebihi batas bulan ini!
                </p>
                <ul class="mt-2 space-y-1">
                    @foreach ($exceededBudgets as $b)
                        <li class="text-xs text-danger flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-danger flex-shrink-0"></span>
                            <span>
                                <span class="font-medium">{{ $b->category->name }}</span>
                                - kebablasan
                                <span class="font-medium">Rp {{ number_format($b->over, 0, ',', '.') }}</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
                <p class="text-xs text-danger mt-2">
                    Naikkan batas budget atau kurangi pengeluaran di kategori tersebut.
                </p>
            </div>
        </div>
    </div>
@endif
