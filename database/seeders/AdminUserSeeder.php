<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create sysadmin if not exists
        if (! User::where('email', 'sysadmin@sipirang.local')->exists()) {
            User::create([
                'name'      => 'Sysadmin SIPIRANG',
                'email'     => 'sysadmin@sipirang.local',
                'password'  => Hash::make('sipirang123'),
                'role'      => 'sysadmin',
                'is_active' => true,
            ]);

            $this->command->info('✅ Sysadmin created: sysadmin@sipirang.local / sipirang123');
        } else {
            $this->command->warn('⚠️  Sysadmin already exists, skipping.');
        }

        // Create default admin if not exists
        if (! User::where('email', 'admin@sipirang.local')->exists()) {
            User::create([
                'name'      => 'Admin SIPIRANG',
                'email'     => 'admin@sipirang.local',
                'password'  => Hash::make('sipirang123'),
                'role'      => 'admin',
                'is_active' => true,
            ]);

            $this->command->info('✅ Admin created: admin@sipirang.local / sipirang123');
        } else {
            $this->command->warn('⚠️  Admin already exists, skipping.');
        }
    }
}
