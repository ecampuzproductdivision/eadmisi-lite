<?php

namespace App\Helpers;

use App\Models\Periode;
use Illuminate\Support\Facades\Cache;

/**
 * PeriodeHelper - Global helper for accessing the currently active academic period.
 *
 * Provides a centralized way to get the active period across the application.
 * Uses caching to minimize DB queries.
 */
class PeriodeHelper
{
    /**
     * Cache key for the active period.
     */
    const CACHE_KEY = 'periode_active';

    /**
     * Cache TTL in seconds (5 minutes).
     */
    const CACHE_TTL = 300;

    /**
     * Get the currently active academic period.
     *
     * @return \App\Models\Periode|null
     */
    public static function getActive()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Periode::active()->first();
        });
    }

    /**
     * Get the active period ID, or null if none is set.
     *
     * @return int|null
     */
    public static function getActiveId()
    {
        $periode = self::getActive();
        return $periode ? $periode->id : null;
    }

    /**
     * Check if an active period is set.
     *
     * @return bool
     */
    public static function hasActive()
    {
        return self::getActive() !== null;
    }

    /**
     * Clear the active period cache.
     * Should be called whenever a period's status_aktif changes.
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get the display label for the active period.
     * Example: "2026/2027 - Ganjil"
     *
     * @return string|null
     */
    public static function getActiveLabel()
    {
        $periode = self::getActive();
        return $periode ? $periode->label : null;
    }
}