<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            [
                'key' => 'general.faculty_name',
                'value' => 'Fakultas Ilmu Pendidikan',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nama fakultas',
            ],
            [
                'key' => 'general.university_name',
                'value' => 'Universitas Negeri Makassar',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nama universitas',
            ],
            [
                'key' => 'general.phone',
                'value' => '(0411) 872-504',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nomor telepon fakultas',
            ],
            [
                'key' => 'general.email',
                'value' => 'fip@unm.ac.id',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Email fakultas',
            ],

            // Booking Rules
            [
                'key' => 'booking.deadline_hours',
                'value' => '24',
                'type' => 'integer',
                'group' => 'booking',
                'description' => 'Batas waktu upload surat dalam jam',
            ],
            [
                'key' => 'booking.max_items_per_cart',
                'value' => '5',
                'type' => 'integer',
                'group' => 'booking',
                'description' => 'Maksimal slot per peminjaman',
            ],
            [
                'key' => 'booking.max_days_advance',
                'value' => '30',
                'type' => 'integer',
                'group' => 'booking',
                'description' => 'Maksimal hari ke depan untuk booking',
            ],

            // PDF
            [
                'key' => 'pdf.header_text',
                'value' => 'SIPIRANG - Sistem Peminjaman Ruangan',
                'type' => 'string',
                'group' => 'pdf',
                'description' => 'Teks header PDF',
            ],
            [
                'key' => 'pdf.footer_text',
                'value' => 'Fakultas Ilmu Pendidikan, Universitas Negeri Makassar',
                'type' => 'string',
                'group' => 'pdf',
                'description' => 'Teks footer PDF',
            ],

            // Notification
            [
                'key' => 'notification.reminder_before_hours',
                'value' => '3',
                'type' => 'integer',
                'group' => 'notification',
                'description' => 'Pengingat H-berapa jam sebelum penggunaan',
            ],
            [
                'key' => 'notification.waitlist_offer_expires_hours',
                'value' => '2',
                'type' => 'integer',
                'group' => 'notification',
                'description' => 'Batas waktu respon penawaran waitlist (jam)',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
