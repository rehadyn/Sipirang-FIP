<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->char('ticket_number', 36)->unique();
            $table->char('access_token', 64)->unique();
            $table->string('borrower_name', 255);
            $table->string('borrower_id_number', 30);
            $table->string('borrower_type', 20)->default('mahasiswa');
            $table->string('borrower_organization', 255)->nullable();
            $table->string('borrower_whatsapp', 20);
            $table->text('purpose');
            $table->string('ktp_file_path', 500)->nullable();
            $table->string('approval_letter_path', 500)->nullable();
            $table->timestamp('approval_letter_uploaded_at')->nullable();
            $table->string('status', 30)->default('pending_upload');
            $table->timestamp('deadline_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->char('qr_token', 36)->unique()->nullable();
            $table->timestamp('qr_verified_at')->nullable();
            $table->string('qr_verified_by', 255)->nullable();
            $table->text('notes_admin')->nullable();
            $table->string('booking_pdf_path', 500)->nullable();
            $table->string('approval_pdf_path', 500)->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_type', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('deadline_at');
            $table->index('borrower_id_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
