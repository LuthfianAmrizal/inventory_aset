<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gedungA = Building::where('code', 'GDA')->first();
        $gedungB = Building::where('code', 'GDB')->first();

        Room::create([
            'building_id' => $gedungB->id,
            'code' => 'LAB-KOMP-1',
            'name' => 'Laboratorium Komputer 1',
            'floor' => 'Lantai 2',
            'capacity' => 30,
            'description' => 'Ruang praktikum komputer mahasiswa',
            'is_active' => true,
        ]);

        Room::create([
            'building_id' => $gedungB->id,
            'code' => 'LAB-KOMP-2',
            'name' => 'Laboratorium Komputer 2',
            'floor' => 'Lantai 2',
            'capacity' => 30,
            'description' => 'Ruang praktikum komputer mahasiswa - Lab 2',
            'is_active' => true,
        ]);

        Room::create([
            'building_id' => $gedungA->id,
            'code' => 'RK-101',
            'name' => 'Ruang Kelas 101',
            'floor' => 'Lantai 1',
            'capacity' => 40,
            'description' => 'Ruang perkuliahan teori',
            'is_active' => true,
        ]);

        Room::create([
            'building_id' => $gedungA->id,
            'code' => 'RD-201',
            'name' => 'Ruang Dosen Utama',
            'floor' => 'Lantai 2',
            'capacity' => 15,
            'description' => 'Ruang kerja dosen tetap',
            'is_active' => true,
        ]);

        Room::create([
            'building_id' => $gedungB->id,
            'code' => 'RR-102',
            'name' => 'Ruang Rapat Utama',
            'floor' => 'Lantai 1',
            'capacity' => 20,
            'description' => 'Ruang rapat administrasi dan staff',
            'is_active' => true,
        ]);

        Room::create([
            'building_id' => $gedungB->id,
            'code' => 'RS-301',
            'name' => 'Ruang Server Pusat',
            'floor' => 'Lantai 3',
            'capacity' => 5,
            'description' => 'Ruang server utama dan perangkat jaringan',
            'is_active' => true,
        ]);
    }
}
