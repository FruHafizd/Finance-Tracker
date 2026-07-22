<?php

namespace App\Livewire\FinancialCalendar;

use App\Models\FinancialReminder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class CalendarPage extends Component
{
    public int $currentYear;
    public int $currentMonth;
    public bool $showForm = false;
    public ?int $reminderIdToDelete = null;
    public ?int $selectedReminderId = null;
    public ?int $expandedDay = null;

    public int $formDay = 1;
    public string $formCategory = 'Tagihan';
    public string $formDescription = '';
    public string $formAmount = '';
    public int $formRemindBefore = 0;

    public function mount(): void
    {
        $now = Carbon::now();
        $this->currentYear = $now->year;
        $this->currentMonth = $now->month;
        $this->formDay = $now->day;
    }

    public function previousMonth(): void
    {
        $this->currentMonth--;
        if ($this->currentMonth < 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        }
    }

    public function nextMonth(): void
    {
        $this->currentMonth++;
        if ($this->currentMonth > 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        }
    }

    #[Computed]
    public function holidaysByDate(): array
    {
        return Cache::remember('holidays_all', 86400, function () {
            try {
                $response = Http::timeout(5)->get('https://raw.githubusercontent.com/guangrei/APIHariLibur_V2/main/calendar.min.json');
                if ($response->successful()) {
                    return $response->json() ?? [];
                }
            } catch (\Exception $e) {
                // Return empty if API fails
            }
            return [];
        });
    }

    #[Computed]
    public function remindersByDay()
    {
        if (!Auth::check()) {
            return collect();
        }

        return FinancialReminder::where('user_id', Auth::id())
            ->forMonth($this->currentYear, $this->currentMonth)
            ->get()
            ->groupBy('day');
    }

    #[Computed]
    public function calendarDays(): array
    {
        $firstOfMonth = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $firstDayOfWeek = $firstOfMonth->dayOfWeek; // 0=Sun
        $daysInMonth = $firstOfMonth->daysInMonth;

        // Hari terakhir bulan sebelumnya
        $prevMonth = $firstOfMonth->copy()->subMonth();
        $daysInPrevMonth = $prevMonth->daysInMonth;

        $days = [];

        // Padding awal: tanggal bulan sebelumnya
        for ($i = $firstDayOfWeek - 1; $i >= 0; $i--) {
            $days[] = ['day' => $daysInPrevMonth - $i, 'type' => 'prev'];
        }

        // Tanggal dalam bulan ini
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $days[] = ['day' => $i, 'type' => 'current'];
        }

        // Padding akhir: tanggal bulan berikutnya
        $totalSlots = count($days) > 35 ? 42 : 35;
        $nextDay = 1;
        while (count($days) < $totalSlots) {
            $days[] = ['day' => $nextDay, 'type' => 'next'];
            $nextDay++;
        }

        return $days;
    }

    public function saveReminder(): void
    {
        $daysInMonth = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->daysInMonth;

        $validated = $this->validate([
            'formDay' => "required|integer|min:1|max:{$daysInMonth}",
            'formCategory' => 'required|in:Investasi,Tabungan,Tagihan,Pemasukan',
            'formDescription' => 'required|string|max:255',
            'formAmount' => 'required|numeric|min:1',
            'formRemindBefore' => 'required|in:0,1,3,7',
        ]);

        FinancialReminder::create([
            'user_id' => Auth::id(),
            'day' => (int) $validated['formDay'],
            'month' => $this->currentMonth,
            'year' => $this->currentYear,
            'category' => $validated['formCategory'],
            'description' => strip_tags($validated['formDescription']),
            'amount' => (int) abs($validated['formAmount']),
            'remind_before' => (int) $validated['formRemindBefore'],
        ]);

        $this->reset(['formDescription', 'formAmount']);
        $this->dispatch('close-modal', 'reminder-form');

        $this->dispatch('notify',
            type: 'success',
            title: 'Berhasil!',
            message: 'Reminder keuangan berhasil ditambahkan.'
        );
    }

    public function selectReminderFromDay(int $id): void
    {
        $this->expandedDay = null;
        $this->selectedReminderId = $id;
    }

    public function confirmDelete(int $id): void
    {
        $this->reminderIdToDelete = $id;
        $this->selectedReminderId = null;
        $this->expandedDay = null;
        $this->dispatch('open-modal', 'delete-reminder');
    }

    public function cancelDelete(): void
    {
        $this->reminderIdToDelete = null;
        $this->dispatch('close-modal', 'delete-reminder');
    }

    public function executeDelete(): void
    {
        if (!$this->reminderIdToDelete) return;

        $reminder = FinancialReminder::where('user_id', Auth::id())->findOrFail($this->reminderIdToDelete);
        $reminder->delete();

        $this->reminderIdToDelete = null;
        $this->dispatch('close-modal', 'delete-reminder');

        $this->dispatch('notify',
            type: 'success',
            title: 'Dihapus',
            message: 'Reminder berhasil dihapus.'
        );
    }
    
    public function getCategoryColors(string $category): array
    {
        return match($category) {
            'Investasi' => ['bg' => 'bg-[#E0F2FE]', 'text' => 'text-[#0369A1]', 'dot' => 'bg-[#0369A1]'],
            'Tabungan' => ['bg' => 'bg-[#E1F5EE]', 'text' => 'text-[#085041]', 'dot' => 'bg-[#085041]'],
            'Tagihan' => ['bg' => 'bg-[#FAECE7]', 'text' => 'text-[#712B13]', 'dot' => 'bg-[#712B13]'],
            'Pemasukan' => ['bg' => 'bg-[#EAF3DE]', 'text' => 'text-[#27500A]', 'dot' => 'bg-[#27500A]'],
            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'dot' => 'bg-gray-600'],
        };
    }

    public function render()
    {
        return view('livewire.financial-calendar.calendar-page');
    }
}
