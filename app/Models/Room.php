<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'building_id',
        'name',
        'code',
        'floor',
        'capacity',
        'room_type',
        'requires_ktp',
        'description',
        'rules',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'requires_ktp' => 'boolean',
        'is_active' => 'boolean',
    ];

    public const TYPES = ['aula', 'kelas', 'lab', 'rapat', 'seminar', 'lainnya'];

    // Relationships
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(RoomPhoto::class)->orderBy('sort_order');
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(RoomFacility::class, 'facility_room', 'room_id', 'facility_id')
            ->withPivot(['quantity', 'notes']);
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function waitlists(): HasMany
    {
        return $this->hasMany(Waitlist::class);
    }

    public function blockedDates(): HasMany
    {
        return $this->hasMany(BlockedDate::class);
    }

    public function operatingHours(): HasMany
    {
        return $this->hasMany(RoomOperatingHours::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('room_type', $type);
    }

    public function scopeOfBuilding($query, $buildingId)
    {
        return $query->where('building_id', $buildingId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Methods
    public function isAvailable($date, $startTime, $endTime): bool
    {
        // Check if blocked
        if ($this->blockedDates()->whereDate('blocked_date', $date)->exists()) {
            return false;
        }

        // Check if time is within operating hours
        if (!$this->isWithinOperatingHours($date, $startTime, $endTime)) {
            return false;
        }

        // Check if there are conflicts with existing bookings
        if ($this->hasConflict($date, $startTime, $endTime)) {
            return false;
        }

        return true;
    }

    public function isWithinOperatingHours($date, $startTime, $endTime): bool
    {
        $dateValue = $date instanceof Carbon ? $date : Carbon::parse($date);
        $dayOfWeek = $dateValue->dayOfWeek;
        
        $operatingHour = $this->operatingHours()
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$operatingHour) {
            // If no operating hours defined, assume always open
            return true;
        }

        if (!$operatingHour->is_open) {
            return false;
        }

        $start = Carbon::parse($startTime)->format('H:i:s');
        $end = Carbon::parse($endTime)->format('H:i:s');
        $open = Carbon::parse($operatingHour->open_time)->format('H:i:s');
        $close = Carbon::parse($operatingHour->close_time)->format('H:i:s');

        return $start >= $open && $end <= $close;
    }

    public function hasConflict($date, $startTime, $endTime): bool
    {
        return BookingItem::withConflict($this->id, $date, $startTime, $endTime)->exists();
    }

    public function getCapacityLabel(): string
    {
        return $this->capacity ? "{$this->capacity} orang" : 'Kapasitas tidak ditentukan';
    }
}

