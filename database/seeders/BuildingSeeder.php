<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        Building::create([
            'name' => 'Gedung A - Administrasi',
            'code' => 'GED-A',
            'address' => 'Lantai 1-3, Kampus FIP UNM',
            'floor_count' => 3,
            'description' => 'Gedung administrasi dan ruang pimpinan',
            'is_active' => true,
        ]);

        Building::create([
            'name' => 'Gedung B - Teori & Laboratorium',
            'code' => 'GED-B',
            'address' => 'Lantai 1-4, Kampus FIP UNM',
            'floor_count' => 4,
            'description' => 'Gedung untuk ruang teori dan laboratorium',
            'is_active' => true,
        ]);

        Building::create([
            'name' => 'Gedung C - Aula Utama',
            'code' => 'GED-C',
            'address' => 'Lantai 1, Kampus FIP UNM',
            'floor_count' => 1,
            'description' => 'Aula utama untuk acara-acara besar',
            'is_active' => true,
        ]);
    }
}
