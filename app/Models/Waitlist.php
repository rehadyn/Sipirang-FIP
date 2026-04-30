<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waitlist extends Model
{
    protected $fillable = [
        'room_id',
        'desired_date',
        'desired_start_time',
        'desired_end_time',
        'borrower_name',
        'borrower_whatsapp',
        'borrower_id_number',
        'is_notified',
        'notified_at',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'desired_date' => 'date',
        'desired_start_time' => 'datetime:H:i:s',
        'desired_end_time' => 'datetime:H:i:s',
        'notified_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_notified' => 'boolean',
    ];

    public const STATUS_WAITING = 'waiting';
    public const STATUS_NOTIFIED = 'notified';
    public const STATUS_CONVERTED = 'converted';
    public const STATUS_EXPIRED = 'expired';

    // Relationships
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    // Scopes
    public function scopeWaiting($query)
    {
        return $query->where('status', self::STATUS_WAITING);
    }

    public function scopeNotified($query)
    {
        return $query->where('status', self::STATUS_NOTIFIED);
    }

    public function scopeForRoom($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('desired_date', $date);
    }

    public function scopeOrderedByDate($query)
    {
        return $query->orderBy('desired_date')->orderBy('desired_start_time');
    }
}
