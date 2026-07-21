<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed the admin user
        User::create([
            'username' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('AdminWedding2026!'),
            'role' => 'admin',
        ]);

        // Seed a regular user for manual testing
        User::create([
            'username' => 'testuser',
            'password' => \Illuminate\Support\Facades\Hash::make('Password123!'),
            'role' => 'user',
        ]);
    }
}
