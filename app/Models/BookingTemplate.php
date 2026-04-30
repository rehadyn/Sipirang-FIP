<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class BookingTemplate extends Model
{
    protected $fillable = [
        'borrower_id_number',
        'template_name',
        'borrower_name',
        'borrower_organization',
        'purpose',
        'template_items',
        'usage_count',
    ];

    protected $casts = [
        'template_items' => 'collection',
    ];

    // Scopes
    public function scopeForBorrower($query, $borrowerIdNumber)
    {
        return $query->where('borrower_id_number', $borrowerIdNumber);
    }

    // Methods
    public function incrementUsageCount(): void
    {
        $this->increment('usage_count');
    }
}
