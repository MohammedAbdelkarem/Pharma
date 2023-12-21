<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class usersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'user2',
            'password' => '22222222',
            'mobile' => '1111111111',
            'email' => 'mohammedak2048@gmail.com',
            'role' => 'owner',
        ]);
        User::create([
            'username' => 'user3',
            'password' => '33333333',
            'mobile' => '2222222222',
            'email' => 'alaa.2019.188@gmail.com',
            'role' => 'pharmacist',
        ]);
    }
}
