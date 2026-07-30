<div class="flex flex-col flex-1 w-full min-h-0" 
    x-data="{ 
        init() {
            window.financialReminders = @js($this->remindersByDay->flatten(1));
        },
        async requestNotificationPermission() {
            try {
                if (!('Notification' in window)) {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'danger', title: 'Gagal', message: 'Browser tidak mendukung notifikasi.' } }));
                    return;
                }
                const permission = await Notification.requestPermission();
                if (permission === 'granted') {
                    this.scheduleReminders();
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', title: 'Berhasil', message: 'Notifikasi berhasil diaktifkan!' } }));
                } else if (permission === 'denied') {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'danger', title: 'Ditolak', message: 'Izin notifikasi diblokir oleh browser Anda.' } }));
                } else {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'amber', title: 'Batal', message: 'Izin notifikasi belum diberikan.' } }));
                }
            } catch (error) {
                console.error('Notification Error:', error);
                window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'danger', title: 'Error', message: 'Terjadi kesalahan sistem saat meminta izin.' } }));
            }
        },
        scheduleReminders() {
            const reminders = window.financialReminders;
            const now = new Date();

            reminders.forEach(reminder => {
                const targetDate = new Date(reminder.year, reminder.month - 1, reminder.day - reminder.remind_before);
                const notifDate = new Date(targetDate.getFullYear(), targetDate.getMonth(), targetDate.getDate(), 8, 0, 0);
                const delay = notifDate - now;

                if (delay > 0) {
                    setTimeout(() => {
                        new Notification('💰 Reminder Keuangan', {
                            body: `${reminder.description} — Rp ${reminder.amount_formatted}`,
                        });
                    }, delay);
                }
            });
        }
    }" 
    x-init="init()"
    @financial-reminders-updated.window="init(); scheduleReminders();">
    
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-border px-4 sm:px-6 lg:px-8 py-4 sm:py-6 flex flex-col sm:flex-row sm:justify-between sm:items-center w-full gap-4 shrink-0">
        <h2 class="font-semibold text-xl text-text leading-tight">
            Kalender Keuangan
        </h2>
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            <button type="button" @click="requestNotificationPermission()" class="btn bg-bg-sidebar border border-border text-text hover:bg-bg transition-colors shadow-sm inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium min-h-[40px]">
                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span class="hidden sm:inline">Aktifkan Notif</span>
                <span class="sm:hidden">Notif</span>
            </button>
            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'reminder-form')" class="btn bg-primary hover:opacity-90 text-white transition-colors shadow-sm inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium min-h-[40px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah
            </button>
        </div>
    </div>

    <!-- Calendar Grid Section -->
    <div class="bg-bg-sidebar overflow-hidden flex-1 min-h-0">
        @include('livewire.financial-calendar.partials.calendar-grid')
    </div>

    <!-- Add Form Modal -->
    @include('livewire.financial-calendar.partials.reminder-form')

    <!-- Modals & Popovers -->
    @include('livewire.financial-calendar.partials.delete-reminder-modal')

    

</div>
