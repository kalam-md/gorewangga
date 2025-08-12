<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin/Petugas Users
        User::create([
            'nama_lengkap' => 'Administrator GOR',
            'username' => 'admin',
            'email' => 'admin@mail.com',
            'no_telp' => '081234567890',
            'password' => Hash::make('admin123123'),
            'role' => 'petugas',
        ]);

        // Penyewa Users
        User::create([
            'nama_lengkap' => 'Nizar Rahman',
            'username' => 'nizar',
            'email' => 'nizar@mail.com',
            'no_telp' => '082111111111',
            'password' => Hash::make('nizar123123'),
            'role' => 'penyewa',
        ]);
    }
}