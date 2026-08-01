<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@sientia.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
                'is_admin' => true,
            ]
        );
    }
}
