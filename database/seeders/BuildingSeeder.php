<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Building::create([
            'code' => 'GDA',
            'name' => 'Gedung A',
            'location' => 'Kampus Utama Sebelah Barat',
            'description' => 'Gedung perkuliahan umum',
            'is_active' => true,
        ]);

        Building::create([
            'code' => 'GDB',
            'name' => 'Gedung B',
            'location' => 'Kampus Utama Sebelah Timur',
            'description' => 'Gedung laboratorium dan administrasi',
            'is_active' => true,
        ]);
    }
}
