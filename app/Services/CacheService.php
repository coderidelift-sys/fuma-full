<?php

namespace App\Services;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Standard cache durations for different types of data
     */
    const DURATIONS = [
        'ui' => 120,        // 2 menit - UI data yang sering berubah
        'list' => 300,      // 5 menit - List data yang jarang berubah
        'stats' => 600,     // 10 menit - Statistics data
        'standings' => 300, // 5 menit - Tournament standings
        'player_stats' => 1800, // 30 menit - Player statistics
        'match_data' => 900,    // 15 menit - Match data
    ];

    /**
     * Remember cache with standard duration
     */
    public static function remember(string $key, string $type, Closure $callback)
    {
        $duration = self::DURATIONS[$type] ?? self::DURATIONS['list'];
        return Cache::remember($key, $duration, $callback);
    }

    /**
     * Remember cache with custom duration
     */
    public static function rememberCustom(string $key, int $seconds, Closure $callback)
    {
        return Cache::remember($key, $seconds, $callback);
    }

    /**
     * Forget cache by key
     */
    public static function forget(string $key): bool
    {
        return Cache::forget($key);
    }

    /**
     * Forget cache by pattern
     */
    public static function forgetPattern(string $pattern): void
    {
        $keys = Cache::get('cache_keys', []);
        foreach ($keys as $key) {
            if (str_contains($key, $pattern)) {
                Cache::forget($key);
            }
        }
    }

    /**
     * Clear all cache
     */
    public static function clear(): bool
    {
        return Cache::flush();
    }

    /**
     * Get cache key with prefix
     */
    public static function key(string $prefix, string $identifier): string
    {
        return "fuma_{$prefix}_{$identifier}";
    }

    /**
     * Get cache key for paginated data
     */
    public static function paginatedKey(string $prefix, int $page, array $filters = []): string
    {
        $filterHash = !empty($filters) ? '_' . md5(serialize($filters)) : '';
        return self::key($prefix, "page_{$page}{$filterHash}");
    }
}
