<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingHelper
{
    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        // Try to get from cache first
        $cached = Cache::get("setting:{$key}");
        if ($cached !== null) {
            return $cached;
        }

        // Get from database
        $setting = Setting::whereKey($key)->first();
        $value = $setting ? $setting->value : $default;

        // Cast value based on type
        if ($setting) {
            $value = self::castValue($value, $setting->type);
            Cache::put("setting:{$key}", $value, 24 * 60); // Cache for 24 hours
        }

        return $value;
    }

    /**
     * Set setting value
     */
    public static function set($key, $value, $type = 'string', $group = 'general')
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group]
        );

        // Clear cache
        Cache::forget("setting:{$key}");
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        return Setting::where('group', $group)->get()->mapWithKeys(function ($item) {
            return [$item->key => self::castValue($item->value, $item->type)];
        });
    }

    /**
     * Cast value based on type
     */
    private static function castValue($value, $type)
    {
        return match ($type) {
            'integer' => (int) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}

if (!function_exists('setting')) {
    /**
     * Get or set settings
     */
    function setting($key = null, $default = null)
    {
        if ($key === null) {
            return app(SettingHelper::class);
        }

        return SettingHelper::get($key, $default);
    }
}
