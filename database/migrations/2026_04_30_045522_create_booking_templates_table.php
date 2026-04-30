<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_templates', function (Blueprint $table) {
            $table->id();
            $table->string('borrower_id_number', 30);
            $table->string('template_name', 255);
            $table->string('borrower_name', 255);
            $table->string('borrower_organization', 255)->nullable();
            $table->text('purpose')->nullable();
            $table->json('template_items');
            $table->unsignedInteger('usage_count')->default(0);
            $table->timestamps();
            
            $table->index('borrower_id_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_templates');
    }
};
