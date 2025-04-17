<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel users (optional, hati-hati di production)
        DB::table('users')->truncate();

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('1234'),
            'role' => 'admin',
        ]);

        // User biasa
        User::create([
            'name' => 'Stuff',
            'email' => 'stuff@example.com',
            'password' => Hash::make('1234'),
            'role' => 'user',
        ]);
    }
}
