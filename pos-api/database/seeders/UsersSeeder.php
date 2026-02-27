<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('secret'),
            ]
        ];

        foreach ($admins as $user) {
            User::firstOrCreate(['email' => $user['email']], $user);
        }
    }
}
