<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'access_token',
        'borrower_name',
        'borrower_id_number',
        'borrower_type',
        'borrower_major',
        'borrower_subject',
        'borrower_organization',
        'responsible_person',
        'borrower_whatsapp',
        'purpose',
        'ktp_file_path',
        'approval_letter_path',
        'approval_letter_uploaded_at',
        'status',
        'deadline_at',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'qr_token',
        'qr_verified_at',
        'qr_verified_by',
        'notes_admin',
        'booking_pdf_path',
        'approval_pdf_path',
        'cancelled_at',
        'cancellation_type',
    ];

    protected $casts = [
        'approval_letter_uploaded_at' => 'datetime',
        'deadline_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'qr_verified_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Status constants
    public const STATUS_PENDING_UPLOAD = 'pending_upload';
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_CHECKED_IN = 'checked_in';
    public const STATUS_COMPLETED = 'completed';

    public const BORROWER_TYPES = ['mahasiswa', 'dosen', 'organisasi', 'lainnya'];
    public const CANCELLATION_TYPES = ['auto_expired', 'admin_rejected', 'user_cancelled'];

    // Time session constants
    public const SESSION_PAGI = 'pagi';
    public const SESSION_SIANG = 'siang';
    public const SESSION_FULLDAY = 'fullday';

    public const SESSION_TYPES = [
        self::SESSION_PAGI => ['label' => 'Pagi (07:00 - 12:00)', 'start' => '07:00', 'end' => '12:00'],
        self::SESSION_SIANG => ['label' => 'Siang (13:00 - 17:00)', 'start' => '13:00', 'end' => '17:00'],
        self::SESSION_FULLDAY => ['label' => 'Fullday (07:00 - 17:00)', 'start' => '07:00', 'end' => '17:00'],
    ];

    // Relationships
    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(BookingDocument::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePendingUpload($query)
    {
        return $query->where('status', self::STATUS_PENDING_UPLOAD);
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', self::STATUS_PENDING_REVIEW);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_PENDING_UPLOAD,
            self::STATUS_PENDING_REVIEW,
            self::STATUS_APPROVED,
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_PENDING_UPLOAD)
            ->where('deadline_at', '<', now());
    }

    // Accessors
    public function getIsExpiredAttribute(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function getCanUploadAttribute(): bool
    {
        return $this->status === self::STATUS_PENDING_UPLOAD && $this->deadline_at > now();
    }

    public function getTimeRemainingAttribute(): ?int
    {
        if ($this->status !== self::STATUS_PENDING_UPLOAD || !$this->deadline_at) {
            return null;
        }
        
        $remaining = now()->diffInSeconds($this->deadline_at, false);
        return $remaining > 0 ? $remaining : 0;
    }

    public function getAdminSlaDeadlineAttribute(): ?\Illuminate\Support\Carbon
    {
        if ($this->status !== self::STATUS_PENDING_REVIEW || !$this->approval_letter_uploaded_at) {
            return null;
        }

        return $this->approval_letter_uploaded_at->addDays(2);
    }

    public function getAdminSlaRemainingAttribute(): ?int
    {
        $deadline = $this->admin_sla_deadline;
        if (!$deadline) {
            return null;
        }

        $remaining = now()->diffInSeconds($deadline, false);
        return $remaining > 0 ? $remaining : 0;
    }
}
