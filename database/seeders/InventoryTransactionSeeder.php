<?php

namespace Database\Seeders;

use App\Models\InventoryTransaction;
use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class InventoryTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pembelianBaru = TransactionType::where('code', 'TRX-IN-01')->first();
        $distribusiBarang = TransactionType::where('code', 'TRX-TRF-01')->first();

        // 1. Transaksi Pembelian Baru (Total Budget 100 Juta)
        InventoryTransaction::create([
            'transaction_type_id' => $pembelianBaru->id,
            'transaction_number' => 'INV-TRX-20260626-0001',
            'budget' => 100000000.00,       // Rp 100.000.000,00
            'realization' => 100000000.00,  // Rp 100.000.000,00
            'transaction_date' => '2026-06-26',
            'notes' => 'Pembelian 10 unit komputer workstation untuk Laboratorium Komputer.',
        ]);

        // 2. Transaksi Distribusi Barang ke Lab 1 (5 Unit)
        InventoryTransaction::create([
            'transaction_type_id' => $distribusiBarang->id,
            'transaction_number' => 'INV-TRX-20260626-0002',
            'budget' => 0.00,
            'realization' => 0.00,
            'transaction_date' => '2026-06-26',
            'notes' => 'Distribusi 5 unit komputer workstation ke Laboratorium Komputer 1.',
        ]);

        // 3. Transaksi Distribusi Barang ke Lab 2 (5 Unit)
        InventoryTransaction::create([
            'transaction_type_id' => $distribusiBarang->id,
            'transaction_number' => 'INV-TRX-20260626-0003',
            'budget' => 0.00,
            'realization' => 0.00,
            'transaction_date' => '2026-06-26',
            'notes' => 'Distribusi 5 unit komputer workstation ke Laboratorium Komputer 2.',
        ]);
    }
}
