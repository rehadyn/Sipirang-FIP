<?php

namespace App\Livewire\Admin;

use App\Helpers\SettingHelper;
use Livewire\Component;

class Settings extends Component
{
    // General
    public string $faculty_name       = '';
    public string $university_name    = '';
    public string $phone              = '';
    public string $email              = '';

    // Booking
    public string|int $deadline_hours      = 5;
    public string|int $max_items_per_cart  = 5;
    public string|int $max_days_advance    = 30;


    public function mount(): void
    {
        $this->faculty_name      = SettingHelper::get('general.faculty_name', '');
        $this->university_name   = SettingHelper::get('general.university_name', '');
        $this->phone             = SettingHelper::get('general.phone', '');
        $this->email             = SettingHelper::get('general.email', '');

        $this->deadline_hours    = SettingHelper::get('booking.deadline_hours', 5);
        $this->max_items_per_cart = SettingHelper::get('booking.max_items_per_cart', 5);
        $this->max_days_advance  = SettingHelper::get('booking.max_days_advance', 30);

    }

    public function saveGeneral(): void
    {
        $this->validate([
            'faculty_name'    => ['required', 'string', 'max:255'],
            'university_name' => ['required', 'string', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:30'],
            'email'           => ['nullable', 'email', 'max:255'],
        ], [], [
            'faculty_name'    => 'nama fakultas',
            'university_name' => 'nama universitas',
            'phone'           => 'nomor telepon',
            'email'           => 'email',
        ]);

        SettingHelper::set('general.faculty_name', $this->faculty_name, 'string', 'general');
        SettingHelper::set('general.university_name', $this->university_name, 'string', 'general');
        SettingHelper::set('general.phone', $this->phone, 'string', 'general');
        SettingHelper::set('general.email', $this->email, 'string', 'general');

        session()->flash('success', 'Pengaturan umum berhasil disimpan.');
    }

    public function saveBooking(): void
    {
        $this->validate([
            'deadline_hours'     => ['required', 'integer', 'min:1', 'max:72'],
            'max_items_per_cart' => ['required', 'integer', 'min:1', 'max:20'],
            'max_days_advance'   => ['required', 'integer', 'min:1', 'max:365'],
        ], [], [
            'deadline_hours'     => 'batas waktu upload',
            'max_items_per_cart' => 'maks. slot per booking',
            'max_days_advance'   => 'maks. hari ke depan',
        ]);

        SettingHelper::set('booking.deadline_hours', (int) $this->deadline_hours, 'integer', 'booking');
        SettingHelper::set('booking.max_items_per_cart', (int) $this->max_items_per_cart, 'integer', 'booking');
        SettingHelper::set('booking.max_days_advance', (int) $this->max_days_advance, 'integer', 'booking');

        session()->flash('success', 'Pengaturan booking berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.settings')->layout('layouts.admin');
    }
}
