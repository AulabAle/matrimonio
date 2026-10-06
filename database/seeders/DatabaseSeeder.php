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
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'password' => \Illuminate\Support\Facades\Hash::make('AdminWedding2026!'),
                'role' => 'admin',
            ]
        );

        // Seed a regular user for manual testing
        User::firstOrCreate(
            ['username' => 'testuser'],
            [
                'password' => \Illuminate\Support\Facades\Hash::make('Password123!'),
                'role' => 'user',
            ]
        );

        // Seed the amici user
        User::firstOrCreate(
            ['username' => 'amici'],
            [
                'password' => \Illuminate\Support\Facades\Hash::make('AmiciWedding2026!'),
                'role' => 'amici',
            ]
        );
        // Seed sample selfies for demonstration if none exist
        if (\App\Models\Selfie::count() === 0) {
            \App\Models\Selfie::create([
                'image_path' => 'selfies/demo1.jpg',
                'caption' => 'Viva gli Sposi! Monica ed Erasmo 🎉',
            ]);
            \App\Models\Selfie::create([
                'image_path' => 'selfies/demo2.png',
                'caption' => 'Un ricordo speciale di questa bellissima giornata ❤️',
            ]);
        }
    }
}
