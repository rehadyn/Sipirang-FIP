<?php

namespace App\Livewire\Guest;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Room;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;

class LiveBoard extends Component
{
    public $selectedDate;
    public $cartItems = [];

    public function mount()
    {
        $this->selectedDate = date('Y-m-d');
        $this->cartItems = Session::get('guest_booking_cart', []);
    }

    public function addToCart($roomId, $sessionKey)
    {
        if (empty($this->selectedDate)) {
            $this->addError('date', 'Pilih tanggal terlebih dahulu.');
            return;
        }

        $room = Room::findOrFail($roomId);
        
        $sessionInfo = Booking::SESSION_TYPES[$sessionKey] ?? null;
        if (!$sessionInfo) {
            $this->addError('session', 'Sesi waktu tidak valid.');
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
                $this->addError('cart', 'Ruangan dan sesi waktu ini sudah ada di keranjang.');
                return;
            }
        }

        if (! $room->isAvailable($this->selectedDate, $startTime, $endTime)) {
            $this->addError('cart', 'Ruangan tidak tersedia pada sesi waktu yang dipilih.');
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
        session()->flash('status', 'Jadwal ruangan berhasil ditambahkan ke keranjang.');
    }

    public function removeFromCart($index)
    {
        if (isset($this->cartItems[$index])) {
            unset($this->cartItems[$index]);
            $this->cartItems = array_values($this->cartItems);
            Session::put('guest_booking_cart', $this->cartItems);
            session()->flash('status', 'Item keranjang berhasil dihapus.');
        }
    }

    public function render()
    {
        $rooms = Room::query()
            ->with(['building', 'facilities'])
            ->active()
            ->ordered()
            ->get();

        $bookedSessions = [];
        if ($this->selectedDate) {
            $bookedItems = BookingItem::query()
                ->where('booking_date', $this->selectedDate)
                ->where('status', 'active')
                ->whereHas('booking', function ($query) {
                    $query->whereNotIn('status', ['rejected', 'expired', 'cancelled']);
                })
                ->get();
                
            foreach ($bookedItems as $item) {
                if ($item->session) {
                    $bookedSessions[$item->room_id][] = $item->session;
                    
                    // Logic Overlap: Jika Fullday, blokir Pagi & Siang
                    if ($item->session === Booking::SESSION_FULLDAY) {
                        $bookedSessions[$item->room_id][] = Booking::SESSION_PAGI;
                        $bookedSessions[$item->room_id][] = Booking::SESSION_SIANG;
                    }
                    
                    // Jika Pagi atau Siang, blokir Fullday
                    if ($item->session === Booking::SESSION_PAGI || $item->session === Booking::SESSION_SIANG) {
                        $bookedSessions[$item->room_id][] = Booking::SESSION_FULLDAY;
                    }
                }
            }
            
            // Uniquify the booked sessions
            foreach ($bookedSessions as $roomId => $sessions) {
                $bookedSessions[$roomId] = array_unique($sessions);
            }
        }

        return view('livewire.guest.live-board', [
            'rooms' => $rooms,
            'bookedSessions' => $bookedSessions,
            'sessionTypes' => Booking::SESSION_TYPES,
        ])->layout('layouts.guest');
    }
}
