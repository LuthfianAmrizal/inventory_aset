<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemType;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elektronik = ItemType::where('code', 'ELK')->first();

        Item::create([
            'item_type_id' => $elektronik->id,
            'code' => 'KOMP-01',
            'name' => 'Komputer Workstation',
            'unit' => 'unit',
            'description' => 'Komputer workstation spesifikasi tinggi untuk laboratorium',
            'is_active' => true,
        ]);
    }
}
