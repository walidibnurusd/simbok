<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'address' => 'makassar',
                'no_hp' => '089797879',
                'password' => Hash::make('password123'), // Hashing password sebelum disimpan
            ],
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'role' => 'user',
                'address' => 'makassar',
                'no_hp' => '089797879',
                'password' => Hash::make('secret123'), // Hashing password sebelum disimpan
            ],
            // Anda bisa menambahkan lebih banyak pengguna di sini
        ]);
    }
}
