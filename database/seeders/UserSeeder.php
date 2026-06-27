<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Dosen',
            'email' => 'dosen@example.com',
            'role' => 'staff_inventory',
            'password' => Hash::make('password'),
        ]);
    }
}
