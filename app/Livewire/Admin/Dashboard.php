<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Room;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total'          => Booking::count(),
            'pending_upload' => Booking::where('status', 'pending_upload')->count(),
            'pending_review' => Booking::where('status', 'pending_review')->count(),
            'approved'       => Booking::where('status', 'approved')->count(),
            'rejected'       => Booking::where('status', 'rejected')->count(),
            'expired'        => Booking::where('status', 'expired')->count(),
            'today'          => Booking::whereDate('created_at', today())->count(),
            'this_month'     => Booking::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];

        $pendingReviews = Booking::with(['items.room', 'reviewer'])
            ->where('status', 'pending_review')
            ->orderBy('approval_letter_uploaded_at')
            ->limit(10)
            ->get();

        $overdueBookings = Booking::with(['items.room'])
            ->where('status', 'pending_review')
            ->whereNotNull('approval_letter_uploaded_at')
            ->where('approval_letter_uploaded_at', '<', now()->subDays(2))
            ->orderBy('approval_letter_uploaded_at')
            ->limit(5)
            ->get();

        $recentBookings = Booking::with(['items.room'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $totalRooms = Room::count();
        $activeRooms = Room::where('is_active', true)->count();

        return view('livewire.admin.dashboard', compact(
            'stats', 'pendingReviews', 'overdueBookings', 'recentBookings', 'totalRooms', 'activeRooms'
        ))->layout('layouts.admin');
    }
}
