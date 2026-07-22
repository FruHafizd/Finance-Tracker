@php
    $dayRemindersAll = $this->remindersByDay->get($expandedDay) ?? collect();
    $dayDate = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, $expandedDay);
@endphp

@if($dayRemindersAll->count())
    <template x-teleport="body">
        {{-- Reads popoverTop/popoverLeft from parent (calendar-grid) scope --}}
        <div x-data="{
                 pTop: 0,
                 pLeft: 0,
                 init() {
                     this.calcPosition();
                 },
                 calcPosition() {
                     const popW = 280;
                     const popH = 320;
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

            <!-- Backdrop -->
            <div class="fixed inset-0 z-[140]" wire:click="$set('expandedDay', null)"></div>

            <!-- Popover Card -->
            <div class="fixed z-[141] bg-bg-sidebar rounded-2xl shadow-xl border border-border w-[17rem] flex flex-col transform transition-all overflow-hidden"
                 :style="'top:' + pTop + 'px; left:' + pLeft + 'px;'">

                <!-- Header -->
                <div class="flex items-center justify-between px-4 pt-4 pb-2">
                    <div>
                        <div class="text-sm font-bold text-text">{{ $dayDate->translatedFormat('l') }}</div>
                        <div class="text-xs text-text-muted">{{ $dayDate->translatedFormat('d F Y') }}</div>
                    </div>
                    <button type="button" wire:click="$set('expandedDay', null)"
                            class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-text hover:bg-bg rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Reminder List -->
                <div class="px-3 pb-3 flex flex-col gap-1.5 max-h-60 overflow-y-auto">
                    @foreach($dayRemindersAll as $reminder)
                        @php $c = $this->getCategoryColors($reminder->category); @endphp
                        <button type="button"
                            wire:click="selectReminderFromDay({{ $reminder->id }})"
                            @click="
                                const rect = $event.currentTarget.getBoundingClientRect();
                                $dispatch('open-reminder-popover', { top: rect.top, left: rect.right + 8 });
                            "
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-bg/80 transition-colors text-left group/item w-full">
                            <div class="w-2 h-2 rounded-full {{ $c['dot'] }} shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-semibold text-text truncate">{{ e($reminder->description) }}</div>
                                <div class="text-[10px] text-text-muted">Rp {{ $reminder->amount_formatted }}</div>
                            </div>
                            <span class="text-[9px] px-1.5 py-0.5 rounded {{ $c['bg'] }} {{ $c['text'] }} font-medium shrink-0">{{ $reminder->category }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </template>
@endif
