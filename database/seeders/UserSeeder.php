<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin users
        User::create([
            'name' => 'Admin Fakultas',
            'email' => 'admin@fip.unm.ac.id',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Pimpinan FIP',
            'email' => 'pimpinan@fip.unm.ac.id',
            'password' => bcrypt('password'),
            'role' => User::ROLE_PIMPINAN,
            'is_active' => true,
        ]);

        // Additional test admin
        User::create([
            'name' => 'Admin Test',
            'email' => 'test.admin@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
        ]);
    }
}
