<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@perfume.test'],
            [
                'name' => 'Perfume Store Admin',
                'password' => 'Password123!',
                'is_admin' => true,
            ]
        );
    }
}