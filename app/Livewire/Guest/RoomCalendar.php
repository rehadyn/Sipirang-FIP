<?php

namespace App\Livewire\Guest;

use App\Models\BookingItem;
use Carbon\Carbon;
use Livewire\Component;

class RoomCalendar extends Component
{
    public int $month;
    public int $year;
    public ?string $selectedDate = null;

    public function mount(): void
    {
        $this->month = (int) now()->month;
        $this->year  = (int) now()->year;
    }

    public function prevMonth(): void
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->month = $date->month;
        $this->year  = $date->year;
        $this->selectedDate = null;
    }

    public function nextMonth(): void
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->month = $date->month;
        $this->year  = $date->year;
        $this->selectedDate = null;
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = ($this->selectedDate === $date) ? null : $date;
    }

    public function render()
    {
        return view('livewire.room-calendar', $this->buildViewData(false))
            ->layout('layouts.guest');
    }

    protected function buildViewData(bool $isAdmin): array
    {
        $startOfMonth = Carbon::create($this->year, $this->month, 1)->locale('id');
        $endOfMonth   = $startOfMonth->copy()->endOfMonth();

        // Build calendar grid starting Monday
        $calendarStart = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $calendarEnd   = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        // Fetch approved booking items for this month
        $bookingItems = BookingItem::with(['booking', 'room.building'])
            ->whereHas('booking', fn($q) => $q->whereIn('status', ['approved', 'checked_in', 'completed']))
            ->whereBetween('booking_date', [
                $startOfMonth->format('Y-m-d'),
                $endOfMonth->format('Y-m-d'),
            ])
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();

        // Group by date string
        $bookingsByDate = $bookingItems->groupBy(fn($item) => $item->booking_date->format('Y-m-d'));

        // Assign a fixed color to each unique room
        $colorPalette = [
            ['dot' => 'bg-indigo-500',  'tag' => 'bg-indigo-50 text-indigo-700 border border-indigo-200',  'badge' => 'bg-indigo-100 text-indigo-800'],
            ['dot' => 'bg-violet-500',  'tag' => 'bg-violet-50 text-violet-700 border border-violet-200',  'badge' => 'bg-violet-100 text-violet-800'],
            ['dot' => 'bg-blue-500',    'tag' => 'bg-blue-50 text-blue-700 border border-blue-200',        'badge' => 'bg-blue-100 text-blue-800'],
            ['dot' => 'bg-emerald-500', 'tag' => 'bg-emerald-50 text-emerald-700 border border-emerald-200','badge' => 'bg-emerald-100 text-emerald-800'],
            ['dot' => 'bg-cyan-500',    'tag' => 'bg-cyan-50 text-cyan-700 border border-cyan-200',        'badge' => 'bg-cyan-100 text-cyan-800'],
            ['dot' => 'bg-amber-500',   'tag' => 'bg-amber-50 text-amber-700 border border-amber-200',    'badge' => 'bg-amber-100 text-amber-800'],
            ['dot' => 'bg-rose-500',    'tag' => 'bg-rose-50 text-rose-700 border border-rose-200',       'badge' => 'bg-rose-100 text-rose-800'],
            ['dot' => 'bg-teal-500',    'tag' => 'bg-teal-50 text-teal-700 border border-teal-200',       'badge' => 'bg-teal-100 text-teal-800'],
            ['dot' => 'bg-orange-500',  'tag' => 'bg-orange-50 text-orange-700 border border-orange-200', 'badge' => 'bg-orange-100 text-orange-800'],
            ['dot' => 'bg-pink-500',    'tag' => 'bg-pink-50 text-pink-700 border border-pink-200',       'badge' => 'bg-pink-100 text-pink-800'],
        ];

        $roomColors = [];
        $colorIndex = 0;
        foreach ($bookingItems->pluck('room')->filter()->unique('id')->sortBy('id') as $room) {
            if (! isset($roomColors[$room->id])) {
                $roomColors[$room->id] = $colorPalette[$colorIndex % count($colorPalette)];
                $colorIndex++;
            }
        }

        // Build week grid
        $weeks   = [];
        $current = $calendarStart->copy();
        while ($current->lte($calendarEnd)) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $dateStr = $current->format('Y-m-d');
                $week[]  = [
                    'date'           => $dateStr,
                    'day'            => $current->day,
                    'isCurrentMonth' => $current->month === $this->month,
                    'isToday'        => $current->isToday(),
                    'bookings'       => $bookingsByDate->get($dateStr, collect()),
                ];
                $current->addDay();
            }
            $weeks[] = $week;
        }

        $selectedItems = $this->selectedDate
            ? $bookingsByDate->get($this->selectedDate, collect())
            : collect();

        // Rooms legend (only rooms with bookings this month)
        $rooms = $bookingItems->pluck('room')->filter()->unique('id')->sortBy('name');

        return [
            'weeks'         => $weeks,
            'monthLabel'    => $startOfMonth->translatedFormat('F Y'),
            'roomColors'    => $roomColors,
            'rooms'         => $rooms,
            'selectedItems' => $selectedItems,
            'isAdmin'       => $isAdmin,
            'totalBooked'   => $bookingItems->count(),
        ];
    }
}
