<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Item;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $komputer = Item::where('code', 'KOMP-01')->first();

        // 5 unit komputer dialokasikan di Lab 1 (LAB-KOMP-1)
        Inventory::create([
            'item_id' => $komputer->id,
            'qty' => 5,
            'price' => 10000000.00, // Rp 10.000.000,00 per unit
            'barcode' => 'BARCODE-KOMP-LAB1-001',
            'expired_date' => '2031-12-31',
            'status' => 'available',
            'description' => '5 unit komputer dialokasikan di Laboratorium Komputer 1.',
        ]);

        // 5 unit komputer dialokasikan di Lab 2 (LAB-KOMP-2)
        Inventory::create([
            'item_id' => $komputer->id,
            'qty' => 5,
            'price' => 10000000.00, // Rp 10.000.000,00 per unit
            'barcode' => 'BARCODE-KOMP-LAB2-001',
            'expired_date' => '2031-12-31',
            'status' => 'available',
            'description' => '5 unit komputer dialokasikan di Laboratorium Komputer 2.',
        ]);
    }
}
