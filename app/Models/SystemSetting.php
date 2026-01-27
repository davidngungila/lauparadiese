<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'group',
    ];

    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        try {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            // Table doesn't exist yet, return default
            \Log::debug('SystemSetting table not found, returning default: ' . $e->getMessage());
            return $default;
        }
    }

    /**
     * Set a setting value by key
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function setValue(string $key, $value): bool
    {
        try {
            $setting = self::firstOrNew(['key' => $key]);
            $setting->value = $value;
            return $setting->save();
        } catch (\Exception $e) {
            // Table doesn't exist yet
            \Log::error('SystemSetting table not found, cannot save: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all settings by group
     *
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByGroup(string $group)
    {
        return self::where('group', $group)->get();
    }
}

