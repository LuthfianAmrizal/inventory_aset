<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionType::create([
            'code' => 'TRX-IN-01',
            'name' => 'Pembelian Baru',
            'direction' => 'in',
            'description' => 'Penambahan aset baru melalui pembelian resmi',
            'is_active' => true,
        ]);

        TransactionType::create([
            'code' => 'TRX-OUT-01',
            'name' => 'Penghapusan Barang',
            'direction' => 'out',
            'description' => 'Pengeluaran barang/aset karena rusak, usang, atau hilang',
            'is_active' => true,
        ]);

        TransactionType::create([
            'code' => 'TRX-TRF-01',
            'name' => 'Distribusi Barang',
            'direction' => 'transfer',
            'description' => 'Penyaluran atau distribusi barang/aset ke unit kerja atau ruangan',
            'is_active' => true,
        ]);

        TransactionType::create([
            'code' => 'TRX-TRF-02',
            'name' => 'Mutasi Barang',
            'direction' => 'transfer',
            'description' => 'Perpindahan barang/aset antar ruangan atau unit kerja',
            'is_active' => true,
        ]);
    }
}
