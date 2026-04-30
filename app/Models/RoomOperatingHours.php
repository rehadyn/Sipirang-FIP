<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomOperatingHours extends Model
{
    protected $table = 'room_operating_hours';

    protected $fillable = [
        'room_id',
        'day_of_week',
        'open_time',
        'close_time',
        'is_open',
    ];

    protected $casts = [
        'is_open' => 'boolean',
    ];

    public const DAYS = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];

    // Relationships
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    // Scopes
    public function scopeForDay($query, $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    public function scopeOpen($query)
    {
        return $query->where('is_open', true);
    }

    // Methods
    public function getDayNameAttribute(): string
    {
        return self::DAYS[$this->day_of_week] ?? '';
    }
}
