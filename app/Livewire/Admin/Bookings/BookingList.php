<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class BookingList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $typeFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    protected $queryString = [
        'search'       => ['except' => ''],
        'statusFilter' => ['except' => '', 'as' => 'status'],
        'typeFilter'   => ['except' => '', 'as' => 'type'],
    ];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }
    public function updatingTypeFilter(): void { $this->resetPage(); }

    public function render()
    {
        $query = Booking::with(['items.room', 'reviewer'])
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('ticket_number', 'like', '%' . $this->search . '%')
                  ->orWhere('borrower_name', 'like', '%' . $this->search . '%')
                  ->orWhere('borrower_whatsapp', 'like', '%' . $this->search . '%');
            }))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->typeFilter, fn($q) => $q->where('borrower_type', $this->typeFilter))
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->orderByDesc('created_at');

        $bookings = $query->paginate(15);

        $statusOptions = [
            'pending_upload'  => 'Menunggu Upload',
            'pending_review'  => 'Menunggu Review',
            'approved'        => 'Disetujui',
            'rejected'        => 'Ditolak',
            'expired'         => 'Kedaluwarsa',
            'cancelled'       => 'Dibatalkan',
            'checked_in'      => 'Check-in',
            'completed'       => 'Selesai',
        ];

        return view('livewire.admin.bookings.list', compact('bookings', 'statusOptions'))
            ->layout('layouts.admin');
    }
}
