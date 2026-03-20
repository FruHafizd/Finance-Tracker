<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Beranda</h2>
    </x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        .db-wrap { font-family:'Plus Jakarta Sans',sans-serif; background:#f4f5f9; min-height:100vh; }
        .db-inner { max-width:1152px; margin:0 auto; padding:28px 16px; display:flex; flex-direction:column; gap:24px; }
        .db-section-label { font-size:10.5px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#a1a9b8; margin-bottom:10px; padding-left:2px; }
        .db-month-badge {
            display:inline-flex; align-items:center; gap:7px;
            background:#fff; border:1px solid #e5e7eb; border-radius:10px;
            padding:7px 14px; font-size:13px; font-weight:600; color:#4b5563;
            box-shadow:0 1px 3px rgba(0,0,0,.05);
        }
        .db-month-badge svg { color:#6366f1; }
    </style>

    <div class="db-wrap">
        <div class="db-inner">

            {{-- Bulan aktif --}}
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                <div>
                    <p style="font-size:20px;font-weight:800;color:#111827;margin:0;">Selamat datang 👋</p>
                    <p style="font-size:13px;color:#9ca3af;margin:4px 0 0 0;">Ringkasan keuangan kamu bulan ini</p>
                </div>
                <div class="db-month-badge">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    {{ now()->translatedFormat('F Y') }}
                </div>
            </div>

            {{-- Summary Cards --}}
            <div>
                <p class="db-section-label">Ringkasan Bulan Ini</p>
                <livewire:home.summary-cards />
            </div>

            {{-- Chart --}}
            <div>
                <p class="db-section-label">Pengeluaran per Kategori</p>
                <livewire:home.expense-chart />
            </div>

        </div>
    </div>
</div>