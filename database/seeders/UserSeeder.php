<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'sysadmin@sipirang.local'], [
            'name'      => 'Sysadmin SIPIRANG',
            'password'  => Hash::make('sipirang123'),
            'role'      => 'sysadmin',
            'is_active' => true,
        ]);

        User::firstOrCreate(['email' => 'admin@sipirang.local'], [
            'name'      => 'Admin SIPIRANG',
            'password'  => Hash::make('sipirang123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);
    }
}
