@php
    $totalWeeks = (int) ceil(count($this->calendarDays) / 7);
@endphp

<!-- 
Tailwind JIT safelist — DO NOT REMOVE.
bg-[#E0F2FE] text-[#0369A1] bg-[#0369A1]
bg-[#E1F5EE] text-[#085041] bg-[#085041]
bg-[#FAECE7] text-[#712B13] bg-[#712B13]
bg-[#EAF3DE] text-[#27500A] bg-[#27500A]
-->

<div class="flex flex-col h-full"
     x-data="{ popoverTop: 0, popoverLeft: 0 }"
     @open-reminder-popover.window="popoverTop = $event.detail.top; popoverLeft = $event.detail.left">
    <!-- Header Navigasi -->
    <div class="flex items-center justify-between px-4 sm:px-6 py-3 border-b border-border shrink-0">
        <button wire:click="previousMonth" class="p-2 flex items-center justify-center text-text-muted hover:text-text hover:bg-bg rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <div class="flex items-center justify-center flex-1">
            <h3 class="text-base sm:text-lg font-bold text-text select-none">
                {{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->translatedFormat('F Y') }}
            </h3>
        </div>
        <button wire:click="nextMonth" class="p-2 flex items-center justify-center text-text-muted hover:text-text hover:bg-bg rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>

    <!-- Grid area: sisa tinggi diisi grid tanggal -->
    <div class="flex flex-col flex-1 min-h-0">
        <div class="grid grid-cols-7 border-b border-border bg-bg shrink-0">
            @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $index => $day)
                <div class="py-2.5 text-center text-[10px] sm:text-xs font-bold uppercase tracking-wider {{ $index === 0 ? 'text-danger' : 'text-text-muted' }}">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        @php
            $prevMonthLabel = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->subMonth()->translatedFormat('M');
            $nextMonthLabel = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->addMonth()->translatedFormat('M');
            $currentMonthLabel = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->translatedFormat('M');
            $shownPrevLabel = false;
        @endphp

        <div class="grid grid-cols-7 flex-1 min-h-0"
             style="grid-template-rows: repeat({{ $totalWeeks }}, minmax(0, 1fr));">
            @foreach($this->calendarDays as $index => $entry)
                @php
                    $dayNum = $entry['day'];
                    $dayType = $entry['type']; // 'prev', 'current', 'next'
                    $isCurrent = $dayType === 'current';
                    $isSunday = $index % 7 === 0;

                    // Hanya proses holiday/reminder/today untuk bulan ini
                    $dateString = $isCurrent ? sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $dayNum) : null;
                    $holiday = $dateString ? ($this->holidaysByDate[$dateString] ?? null) : null;
                    $isHoliday = $holiday && isset($holiday['holiday']) && $holiday['holiday'];
                    $isToday = $isCurrent && $dayNum == now()->day && $currentMonth == now()->month && $currentYear == now()->year;
                    $dayReminders = $isCurrent ? ($this->remindersByDay->get($dayNum) ?? collect()) : collect();

                    // Tentukan label bulan yang perlu ditampilkan
                    $monthLabel = null;
                    if ($dayType === 'prev' && !$shownPrevLabel) {
                        $monthLabel = $prevMonthLabel;
                        $shownPrevLabel = true;
                    } elseif ($dayType === 'next' && $dayNum === 1) {
                        $monthLabel = $nextMonthLabel;
                    } elseif ($isCurrent && $dayNum === 1) {
                        $monthLabel = $currentMonthLabel;
                    }
                @endphp
                <div class="min-h-0 p-1 sm:p-2 border-b border-r border-border relative flex flex-col overflow-hidden {{ !$isCurrent ? 'bg-bg/30' : '' }} {{ $isToday ? 'bg-bg' : ($isCurrent ? 'hover:bg-bg/80' : '') }} transition-colors group">
                    <div class="flex justify-center sm:justify-start items-start shrink-0">
                        @if($monthLabel)
                            {{-- Tampilan dengan label bulan (lebih lebar) --}}
                            <span class="flex items-center justify-center gap-0.5 px-1.5 h-6 sm:h-7 text-xs sm:text-sm rounded-full
                                @if(!$isCurrent)
                                    text-text-muted/40 font-normal
                                @elseif($isToday)
                                    bg-primary text-white shadow-md shadow-border font-semibold
                                @elseif($isSunday || $isHoliday)
                                    text-danger font-semibold
                                @else
                                    text-text font-semibold
                                @endif
                            ">
                                {{ $dayNum }}
                                <span class="text-[9px] sm:text-[10px] font-normal {{ !$isCurrent ? 'text-text-muted/40' : ($isToday ? 'text-white/80' : 'text-text-muted') }}">{{ $monthLabel }}</span>
                            </span>
                        @else
                            {{-- Tampilan biasa (angka saja) --}}
                            <span class="flex items-center justify-center w-6 h-6 sm:w-7 sm:h-7 text-xs sm:text-sm rounded-full
                                @if(!$isCurrent)
                                    text-text-muted/40 font-normal
                                @elseif($isToday)
                                    bg-primary text-white shadow-md shadow-border font-semibold
                                @elseif($isSunday || $isHoliday)
                                    text-danger font-semibold
                                @else
                                    text-text font-semibold
                                @endif
                            ">
                                {{ $dayNum }}
                            </span>
                        @endif
                    </div>

                    @if($isCurrent)
                        @if($isHoliday)
                            <div class="text-[9px] sm:text-[10px] text-danger font-medium leading-tight mt-0.5 sm:mt-1 text-center sm:text-left truncate shrink-0" title="{{ $holiday['summary'][0] ?? '' }}">
                                {{ $holiday['summary'][0] ?? '' }}
                            </div>
                        @endif

                        <div class="mt-1 sm:mt-1.5 flex flex-col gap-1 items-center sm:items-stretch overflow-hidden flex-1">
                            @foreach($dayReminders->take(2) as $reminder)
                                @php $c = $this->getCategoryColors($reminder->category); @endphp
                                <button type="button"
                                    wire:click="$set('selectedReminderId', {{ $reminder->id }})"
                                    @click="
                                        const rect = $event.currentTarget.getBoundingClientRect();
                                        $dispatch('open-reminder-popover', { top: rect.bottom + 4, left: rect.left });
                                    "
                                    class="hidden sm:block text-[10px] px-1.5 py-0.5 rounded {{ $c['bg'] }} {{ $c['text'] }} truncate font-medium text-left hover:brightness-95 transition-all w-full"
                                    title="{{ e($reminder->description) }}">
                                    {{ e($reminder->description) }}
                                </button>
                                <button type="button"
                                    wire:click="$set('selectedReminderId', {{ $reminder->id }})"
                                    @click="
                                        const rect = $event.currentTarget.getBoundingClientRect();
                                        $dispatch('open-reminder-popover', { top: rect.bottom + 4, left: rect.left });
                                    "
                                    class="sm:hidden w-2 h-2 rounded-full {{ $c['dot'] }} hover:scale-110 transition-transform"
                                    title="{{ e($reminder->description) }}"></button>
                            @endforeach
                            @if($dayReminders->count() > 2)
                                <button type="button"
                                    wire:click="$set('expandedDay', {{ $dayNum }})"
                                    @click="
                                        const rect = $event.currentTarget.getBoundingClientRect();
                                        $dispatch('open-reminder-popover', { top: rect.bottom + 4, left: rect.left });
                                    "
                                    class="hidden sm:block text-[10px] text-text-muted font-medium px-1 hover:text-text transition-colors">
                                    +{{ $dayReminders->count() - 2 }} lagi
                                </button>
                                <button type="button"
                                    wire:click="$set('expandedDay', {{ $dayNum }})"
                                    @click="
                                        const rect = $event.currentTarget.getBoundingClientRect();
                                        $dispatch('open-reminder-popover', { top: rect.bottom + 4, left: rect.left });
                                    "
                                    class="sm:hidden text-[9px] text-text-muted font-medium hover:text-text transition-colors">
                                    +{{ $dayReminders->count() - 2 }}
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @if($selectedReminderId)
        @include('livewire.financial-calendar.partials.reminder-detail-popover')
    @endif
    @if($expandedDay)
        @include('livewire.financial-calendar.partials.day-reminders-popover')
    @endif
</div>