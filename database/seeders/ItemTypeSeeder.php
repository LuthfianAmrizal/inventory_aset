<?php

namespace Database\Seeders;

use App\Models\ItemType;
use Illuminate\Database\Seeder;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemType::create([
            'code' => 'ELK',
            'name' => 'Elektronik',
            'description' => 'Barang-barang elektronik seperti komputer, laptop, proyektor',
            'is_active' => true,
        ]);

        ItemType::create([
            'code' => 'FNT',
            'name' => 'Furnitur',
            'description' => 'Peralatan mebel seperti meja, kursi, lemari',
            'is_active' => true,
        ]);
    }
}
