<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waitlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->date('desired_date');
            $table->time('desired_start_time');
            $table->time('desired_end_time');
            $table->string('borrower_name', 255);
            $table->string('borrower_whatsapp', 20);
            $table->string('borrower_id_number', 30)->nullable();
            $table->boolean('is_notified')->default(false)->index();
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('status', 20)->default('waiting')->index();
            $table->timestamps();
            
            $table->index(['room_id', 'desired_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waitlists');
    }
};
