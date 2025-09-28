<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'MD Rakibul Hasan',
            'email' => 'rakibulhasan.rh895@gmail.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'rakibulhasan.rh890@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
