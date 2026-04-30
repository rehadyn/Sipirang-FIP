<?php

namespace Database\Seeders;

use App\Models\RoomFacility;
use Illuminate\Database\Seeder;

class RoomFacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['name' => 'AC / Pendingin Ruangan', 'icon' => 'wind'],
            ['name' => 'Proyektor', 'icon' => 'projector'],
            ['name' => 'Layar / Screen', 'icon' => 'monitor'],
            ['name' => 'Papan Tulis Putih', 'icon' => 'pen-tool'],
            ['name' => 'Papan Tulis Hitam', 'icon' => 'pen-tool'],
            ['name' => 'Microphone & Sound System', 'icon' => 'mic'],
            ['name' => 'Meja & Kursi Ergonomis', 'icon' => 'chair'],
            ['name' => 'WiFi / Internet', 'icon' => 'wifi'],
            ['name' => 'Kamera CCTV', 'icon' => 'camera'],
            ['name' => 'Lemari Arsip', 'icon' => 'archive'],
        ];

        foreach ($facilities as $facility) {
            RoomFacility::create($facility);
        }
    }
}
