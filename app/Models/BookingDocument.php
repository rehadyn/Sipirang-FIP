<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingDocument extends Model
{
    protected $fillable = [
        'booking_id',
        'document_type',
        'file_path',
        'original_filename',
        'file_size',
        'mime_type',
        'uploaded_by',
    ];

    public const TYPE_KTP = 'ktp';
    public const TYPE_APPROVAL_LETTER = 'approval_letter';
    public const TYPE_BOOKING_RECEIPT_PDF = 'booking_receipt_pdf';
    public const TYPE_FINAL_APPROVAL_PDF = 'final_approval_pdf';
    public const TYPE_OTHER = 'other';

    public const UPLOADED_BY_BORROWER = 'borrower';
    public const UPLOADED_BY_ADMIN = 'admin';
    public const UPLOADED_BY_SYSTEM = 'system';

    // Relationships
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // Scopes
    public function scopeOfType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopeUploadedBy($query, $uploadedBy)
    {
        return $query->where('uploaded_by', $uploadedBy);
    }
}
