<?php

namespace App\Livewire\Guest;

use App\Exceptions\BookingException;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\FileStorageService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class Checkout extends Component
{
    use WithFileUploads;

    public $cartItems = [];
    public $needsKtp = false;

    public $borrower_name = '';
    public $borrower_id_number = '';
    public $borrower_type = '';
    public $borrower_major = '';
    public $borrower_subject = '';
    public $borrower_organization = '';
    public $responsible_person = '';
    public $borrower_whatsapp = '';
    public $purpose = '';
    public $ktp_file;

    public function mount()
    {
        $this->cartItems = Session::get('guest_booking_cart', []);
        
        if (empty($this->cartItems)) {
            session()->flash('status', 'Keranjang masih kosong. Silakan pilih ruangan terlebih dahulu.');
            return redirect()->route('guest.bookings.rooms');
        }

        $this->needsKtp = collect($this->cartItems)->contains('requires_ktp', true);
        $this->borrower_type = Booking::BORROWER_TYPES[0] ?? 'mahasiswa';
    }

    public function submit(BookingService $bookingService, FileStorageService $fileStorageService)
    {
        $rules = [
            'borrower_name' => ['required', 'string', 'max:255'],
            'borrower_id_number' => ['required', 'string', 'max:30'],
            'borrower_type' => ['required', 'in:' . implode(',', Booking::BORROWER_TYPES)],
            'borrower_whatsapp' => ['required', 'string', 'max:20'],
            'purpose' => ['required', 'string'],
        ];

        // Dynamic Validation based on Borrower Type
        if ($this->borrower_type === 'mahasiswa') {
            $rules['borrower_major'] = ['required', 'string', 'max:255'];
            $rules['borrower_subject'] = ['nullable', 'string', 'max:255'];
            $rules['borrower_organization'] = ['nullable', 'string', 'max:255'];
        } elseif ($this->borrower_type === 'organisasi') {
            $rules['borrower_organization'] = ['required', 'string', 'max:255'];
            $rules['responsible_person'] = ['required', 'string', 'max:255'];
        } elseif ($this->borrower_type === 'dosen') {
            $rules['borrower_major'] = ['required', 'string', 'max:255'];
        }

        if ($this->needsKtp) {
            $rules['ktp_file'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
        }

        $validatedData = $this->validate($rules);

        $ktpFilePath = null;
        if ($this->ktp_file) {
            $ktpFilePath = $fileStorageService->storeKTP($this->ktp_file);
        }

        $items = collect($this->cartItems)->map(function (array $item) {
            return Arr::only($item, ['room_id', 'booking_date', 'session', 'start_time', 'end_time', 'notes']);
        })->values()->all();

        try {
            $booking = $bookingService->createBooking(array_merge($validatedData, [
                'ktp_file_path' => $ktpFilePath,
                'items' => $items,
            ]));
        } catch (BookingException $exception) {
            $this->addError('cart', $exception->getMessage());
            return;
        }

        Session::forget('guest_booking_cart');

        session()->flash('status', 'Booking berhasil dibuat. Mohon upload Surat Persetujuan WD 2 sebelum batas waktu habis.');
        return redirect()->route('tracking.show', [
            'ticketNumber' => $booking->ticket_number, 
            'phone' => $booking->borrower_whatsapp
        ]);
    }

    public function render()
    {
        return view('livewire.guest.checkout', [
            'borrowerTypes' => Booking::BORROWER_TYPES,
        ])->layout('layouts.guest');
    }
}
