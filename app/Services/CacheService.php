<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Get cached data or execute callback and cache the result.
     */
    public static function remember(string $key, int $ttl, callable $callback): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Clear cache by pattern.
     */
    public static function clearByPattern(string $pattern): void
    {
        $keys = Cache::get($pattern . '_keys', []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget($pattern . '_keys');
    }

    /**
     * Register a cache key under a pattern for later clearing.
     */
    public static function registerKey(string $pattern, string $key): void
    {
        $keys = Cache::get($pattern . '_keys', []);
        if (!in_array($key, $keys)) {
            $keys[] = $key;
            Cache::put($pattern . '_keys', $keys, 3600);
        }
    }

    /**
     * Clear all homepage related cache.
     */
    public static function clearHomepageCache(): void
    {
        Cache::forget('homepage_stats');
        Cache::forget('homepage_books');
        Cache::forget('homepage_blogs');
        Cache::forget('homepage_exams');
    }
}
