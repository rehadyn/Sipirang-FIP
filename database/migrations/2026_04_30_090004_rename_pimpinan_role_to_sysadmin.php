<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'pimpinan')
            ->update(['role' => 'sysadmin']);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('role', 'sysadmin')
            ->update(['role' => 'pimpinan']);
    }
};
