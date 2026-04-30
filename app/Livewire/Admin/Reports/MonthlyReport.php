<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Booking;
use Livewire\Component;

class MonthlyReport extends Component
{
    public int $month;
    public int $year;
    public bool $previewed = false;
    public array $summary = [];

    public function mount(): void
    {
        $this->month = (int) now()->format('m');
        $this->year  = (int) now()->format('Y');
    }

    public function preview(): void
    {
        $this->validate([
            'month' => ['required', 'integer', 'between:1,12'],
            'year'  => ['required', 'integer', 'min:2020', 'max:' . (date('Y') + 1)],
        ]);

        $bookings = Booking::whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->get();

        $this->summary = [
            'total'          => $bookings->count(),
            'approved'       => $bookings->where('status', 'approved')->count(),
            'rejected'       => $bookings->where('status', 'rejected')->count(),
            'pending'        => $bookings->whereIn('status', ['pending_upload', 'pending_review'])->count(),
            'expired'        => $bookings->where('status', 'expired')->count(),
            'checked_in'     => $bookings->where('status', 'checked_in')->count(),
        ];

        $this->previewed = true;
    }

    public function render()
    {
        $years = range(date('Y'), 2020);

        return view('livewire.admin.reports.monthly', compact('years'))->layout('layouts.admin');
    }
}
