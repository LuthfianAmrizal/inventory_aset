<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ItemTypeSeeder::class,
            BuildingSeeder::class,
            RoomSeeder::class,
            ItemSeeder::class,
            TransactionTypeSeeder::class,
            InventorySeeder::class,
            InventoryTransactionSeeder::class,
        ]);
    }
}
