<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Room;
use App\Models\RoomOperatingHours;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // Gedung A rooms
        $buildingA = Building::where('code', 'GED-A')->first();
        $this->createRoomsForBuilding($buildingA, [
            [
                'name' => 'Ruang Rapat Dekanat',
                'code' => 'GED-A-101',
                'floor' => 1,
                'capacity' => 20,
                'room_type' => 'rapat',
                'requires_ktp' => false,
                'description' => 'Ruang rapat untuk pimpinan dan staf',
                'rules' => 'Dilarang merokok, menjaga kebersihan, no food & drink selain air mineral',
                'sort_order' => 1,
                'facilities' => [2, 3, 6], // Proyektor, Layar, Mic
            ],
            [
                'name' => 'Ruang Tamu Dekanat',
                'code' => 'GED-A-102',
                'floor' => 1,
                'capacity' => 15,
                'room_type' => 'rapat',
                'requires_ktp' => false,
                'description' => 'Ruang tamu untuk menerima tamu',
                'rules' => 'Dilarang merokok',
                'sort_order' => 2,
                'facilities' => [1, 7], // AC, Meja Kursi
            ],
        ]);

        // Gedung B rooms
        $buildingB = Building::where('code', 'GED-B')->first();
        $this->createRoomsForBuilding($buildingB, [
            [
                'name' => 'Ruang Kelas 101',
                'code' => 'GED-B-101',
                'floor' => 1,
                'capacity' => 40,
                'room_type' => 'kelas',
                'requires_ktp' => false,
                'description' => 'Ruang kelas standar dengan kapasitas 40 orang',
                'rules' => 'Dilarang merokok, menjaga kebersihan, rapikan meja kursi sebelum keluar',
                'sort_order' => 1,
                'facilities' => [1, 2, 3, 4, 8], // AC, Proyektor, Layar, Papan Putih, WiFi
            ],
            [
                'name' => 'Lab Komputer',
                'code' => 'GED-B-201',
                'floor' => 2,
                'capacity' => 30,
                'room_type' => 'lab',
                'requires_ktp' => true,
                'description' => 'Laboratorium komputer dengan 30 unit',
                'rules' => 'Wajib login dengan akun, jangan modifikasi setting, laporkan kerusakan',
                'sort_order' => 2,
                'facilities' => [1, 8, 9], // AC, WiFi, CCTV
            ],
            [
                'name' => 'Lab Fisika',
                'code' => 'GED-B-202',
                'floor' => 2,
                'capacity' => 25,
                'room_type' => 'lab',
                'requires_ktp' => true,
                'description' => 'Laboratorium fisika dengan alat-alat eksperimen',
                'rules' => 'Wajib menggunakan APD, dilarang bermain-main dengan alat, lapor asisten',
                'sort_order' => 3,
                'facilities' => [1, 9], // AC, CCTV
            ],
        ]);

        // Gedung C rooms
        $buildingC = Building::where('code', 'GED-C')->first();
        $this->createRoomsForBuilding($buildingC, [
            [
                'name' => 'Aula Utama',
                'code' => 'GED-C-101',
                'floor' => 1,
                'capacity' => 200,
                'room_type' => 'aula',
                'requires_ktp' => false,
                'description' => 'Aula utama untuk acara besar, seminar, dan wisuda',
                'rules' => 'Hubungi admin untuk acara besar, jaga kebersihan, rapikan kursi setelah acara',
                'sort_order' => 1,
                'facilities' => [1, 2, 3, 6, 8], // AC, Proyektor, Layar, Mic, WiFi
            ],
        ]);

        // Add operating hours for all rooms (Monday-Friday: 08:00-16:00, Saturday: 08:00-12:00)
        $rooms = Room::all();
        foreach ($rooms as $room) {
            // Monday-Friday
            for ($day = 1; $day <= 5; $day++) {
                RoomOperatingHours::create([
                    'room_id' => $room->id,
                    'day_of_week' => $day,
                    'open_time' => '08:00:00',
                    'close_time' => '16:00:00',
                    'is_open' => true,
                ]);
            }
            // Saturday
            RoomOperatingHours::create([
                'room_id' => $room->id,
                'day_of_week' => 6,
                'open_time' => '08:00:00',
                'close_time' => '12:00:00',
                'is_open' => true,
            ]);
            // Sunday - closed
            RoomOperatingHours::create([
                'room_id' => $room->id,
                'day_of_week' => 0,
                'open_time' => '00:00:00',
                'close_time' => '00:00:00',
                'is_open' => false,
            ]);
        }
    }

    private function createRoomsForBuilding($building, $roomsData)
    {
        foreach ($roomsData as $roomData) {
            $facilities = $roomData['facilities'];
            unset($roomData['facilities']);
            
            $room = Room::create(array_merge($roomData, [
                'building_id' => $building->id,
            ]));

            // Attach facilities
            if (!empty($facilities)) {
                $room->facilities()->sync($facilities);
            }
        }
    }
}
