<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockedDate extends Model
{
    protected $table = 'blocked_dates';

    protected $fillable = [
        'room_id',
        'blocked_date',
        'reason',
        'notes',
    ];

    protected $casts = [
        'blocked_date' => 'date',
    ];

    // Relationships
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class)->withTrashed();
    }

    // Scopes
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('blocked_date', $date);
    }

    public function scopeForRoom($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('blocked_date', '>=', now()->toDateString())->orderBy('blocked_date');
    }
}
