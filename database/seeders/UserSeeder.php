<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan constraint sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->delete(); // ganti truncate() jadi delete()
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('1234'),
            'role' => 'admin',
        ]);

        // Staff
        User::create([
            'name' => 'Stuff',
            'email' => 'stuff@example.com',
            'password' => Hash::make('1234'),
            'role' => 'user',
        ]);
    }
}

