<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoomFacility extends Model
{
    protected $table = 'room_facilities';

    protected $fillable = [
        'name',
        'icon',
    ];

    public $timestamps = true;

    // Relationships
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'facility_room', 'facility_id', 'room_id')
            ->withPivot(['quantity', 'notes']);
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }
}

