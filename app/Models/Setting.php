<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public $timestamps = false;

    protected $table = 'settings';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'slogan',
        'address',
        'term',
        'session',
        'segment',
        'closed',
        'resumption',
        'timezone',
        'scratch_card',
        'bulk_sms',
        'maintenance_mode',
    ];

    protected function casts(): array
    {
        return [
            'scratch_card' => 'integer',
            'bulk_sms' => 'integer',
            'maintenance_mode' => 'integer',
        ];
    }

    /**
     * Return null when a segment is the placeholder so the UI never displays "No Segment".
     */
    public function getSegmentAttribute(): null
    {
        return null;
    }

    private const CACHE_KEY = 'school_settings';

    private const CACHE_TTL_SECONDS = 300;

    /**
     * Get site settings (cached).
     */
    public static function getCached(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            $row = self::first();

            return $row ? $row->toArray() : [];
        });
    }

    public static function clearSettingsCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
