<?php

namespace App\Livewire\Guest;

use App\Models\BlockedDate;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;

class LiveBoard extends Component
{
    public $selectedDate;
    public $cartItems = [];
    public $buildingFilter = '';
    public $search = '';

    public function mount()
    {
        $this->selectedDate = date('Y-m-d');
        $this->cartItems = Session::get('guest_booking_cart', []);
    }

    public function updatedBuildingFilter(): void
    {
        // reset hook reserved for analytics or paging
    }

    public function updatedSearch(): void
    {
        // debounce handled di blade via wire:model.live.debounce.300ms
    }

    public function addToCart($roomId, $sessionKey)
    {
        if (empty($this->selectedDate)) {
            $this->dispatch('toast', message: 'Pilih tanggal terlebih dahulu.', type: 'error');
            return;
        }

        if ($this->selectedDate < date('Y-m-d')) {
            $this->dispatch('toast', message: 'Tidak dapat memesan untuk tanggal yang sudah lewat.', type: 'error');
            return;
        }

        $room = Room::findOrFail($roomId);

        $sessionInfo = Booking::SESSION_TYPES[$sessionKey] ?? null;
        if (!$sessionInfo) {
            $this->dispatch('toast', message: 'Sesi waktu tidak valid.', type: 'error');
            return;
        }

        $startTime = $sessionInfo['start'];
        $endTime = $sessionInfo['end'];

        foreach ($this->cartItems as $existingItem) {
            if (
                (int) $existingItem['room_id'] === (int) $room->id
                && $existingItem['booking_date'] === $this->selectedDate
                && $existingItem['session'] === $sessionKey
            ) {
                $this->dispatch('toast', message: 'Sesi ini sudah ada di keranjang.', type: 'error');
                return;
            }
        }

        // Specific reason for unavailability
        if ($room->blockedDates()->whereDate('blocked_date', $this->selectedDate)->exists()) {
            $this->dispatch('toast', message: 'Ruangan diblokir pada tanggal ini.', type: 'error');
            return;
        }

        if ($room->hasConflict($this->selectedDate, $startTime, $endTime)) {
            $this->dispatch('toast', message: 'Sesi ini sudah dipesan oleh peminjam lain.', type: 'error');
            return;
        }

        $this->cartItems[] = [
            'cart_key' => (string) Str::uuid(),
            'room_id' => $room->id,
            'room_name' => $room->name,
            'room_code' => $room->code,
            'requires_ktp' => (bool) $room->requires_ktp,
            'booking_date' => $this->selectedDate,
            'session' => $sessionKey,
            'session_label' => $sessionInfo['label'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'notes' => null,
        ];

        Session::put('guest_booking_cart', $this->cartItems);
        $this->dispatch('toast', message: "{$room->name} · {$sessionInfo['label']} ditambahkan.", type: 'success');
    }

    public function removeFromCart($index)
    {
        if (isset($this->cartItems[$index])) {
            unset($this->cartItems[$index]);
            $this->cartItems = array_values($this->cartItems);
            Session::put('guest_booking_cart', $this->cartItems);
            $this->dispatch('toast', message: 'Item dihapus dari keranjang.', type: 'success');
        }
    }

    public function render()
    {
        $totalRooms = Room::query()->active()->count();

        $rooms = Room::query()
            ->with(['building'])
            ->active()
            ->when($this->buildingFilter, fn ($q) => $q->where('building_id', $this->buildingFilter))
            ->when(trim($this->search) !== '', function ($q) {
                $term = '%' . trim($this->search) . '%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                          ->orWhere('code', 'like', $term)
                          ->orWhereHas('building', fn ($b) => $b->where('name', 'like', $term)->orWhere('code', 'like', $term));
                });
            })
            ->ordered()
            ->get();

        $buildings = Building::query()
            ->whereHas('rooms', fn ($q) => $q->active())
            ->orderBy('name')
            ->get();

        $bookedSessions = [];
        $blockedRoomIds = [];

        if ($this->selectedDate) {
            // Blocked dates pada tanggal yang dipilih
            $blockedRoomIds = BlockedDate::whereDate('blocked_date', $this->selectedDate)
                ->pluck('room_id')
                ->map(fn ($id) => (int) $id)
                ->toArray();

            // Existing bookings yang menjadi konflik
            $bookedItems = BookingItem::query()
                ->whereDate('booking_date', $this->selectedDate)
                ->where('status', 'active')
                ->whereHas('booking', function ($query) {
                    $query->whereNotIn('status', ['rejected', 'expired', 'cancelled']);
                })
                ->get();

            foreach ($bookedItems as $item) {
                if ($item->session) {
                    $bookedSessions[$item->room_id][] = $item->session;

                    if ($item->session === Booking::SESSION_FULLDAY) {
                        $bookedSessions[$item->room_id][] = Booking::SESSION_PAGI;
                        $bookedSessions[$item->room_id][] = Booking::SESSION_SIANG;
                    }

                    if ($item->session === Booking::SESSION_PAGI || $item->session === Booking::SESSION_SIANG) {
                        $bookedSessions[$item->room_id][] = Booking::SESSION_FULLDAY;
                    }
                }
            }

            foreach ($bookedSessions as $roomId => $sessions) {
                $bookedSessions[$roomId] = array_unique($sessions);
            }
        }

        $availableCount = $rooms->filter(function ($room) use ($bookedSessions, $blockedRoomIds) {
            if (in_array((int) $room->id, $blockedRoomIds, true)) {
                return false;
            }
            $bookedForRoom = $bookedSessions[$room->id] ?? [];
            $isFull = count(array_intersect($bookedForRoom, [Booking::SESSION_PAGI, Booking::SESSION_SIANG])) >= 2
                || in_array(Booking::SESSION_FULLDAY, $bookedForRoom);
            return ! $isFull;
        })->count();

        return view('livewire.guest.live-board', [
            'rooms' => $rooms,
            'buildings' => $buildings,
            'bookedSessions' => $bookedSessions,
            'blockedRoomIds' => $blockedRoomIds,
            'sessionTypes' => Booking::SESSION_TYPES,
            'availableCount' => $availableCount,
            'totalRooms' => $totalRooms,
        ])->layout('layouts.guest');
    }
}
