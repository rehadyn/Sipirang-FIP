<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained('buildings')->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('code', 30)->unique();
            $table->unsignedTinyInteger('floor')->default(1);
            $table->unsignedSmallInteger('capacity')->nullable();
            $table->string('room_type', 50)->index();
            $table->boolean('requires_ktp')->default(false);
            $table->text('description')->nullable();
            $table->text('rules')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
