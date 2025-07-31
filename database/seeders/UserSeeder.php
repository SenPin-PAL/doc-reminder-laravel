<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menggunakan firstOrCreate untuk menghindari duplikasi jika seeder dijalankan berkali-kali
        User::firstOrCreate(
            // Kunci untuk mencari (apakah user dengan email ini sudah ada?)
            ['email' => 'test@example.com'],
            // Data yang akan dibuat jika tidak ditemukan
            [
                'name' => 'Test User',
                'password' => Hash::make('password'), // Password wajib di-hash!
            ]
        );
    }
}