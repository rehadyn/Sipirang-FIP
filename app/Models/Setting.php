<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    // Scopes
    public function scopeByKey($query, $key)
    {
        return $query->where('key', $key);
    }

    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    // Static getters
    public static function getValue($key, $default = null)
    {
        $setting = static::whereKey($key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value, $type = 'string', $group = 'general')
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );
    }
}
