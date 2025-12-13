<?php

namespace Modules\Report\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchHistoryHelper
{
    /**
     * Record a search query to the search history table
     *
     * @param  string  $keyword  The search keyword
     * @param  string|null  $platform  Platform (ios, android, web)
     * @param  bool  $isOperator  Whether the search is from an operator
     * @param  bool  $fromB2B  Whether the search is from B2B
     */
    public static function record(string $keyword, ?string $platform = null, bool $isOperator = false, bool $fromB2B = false): void
    {
        if (empty(trim($keyword))) {
            return;
        }

        try {
            $request = request();

            // Detect platform from user agent if not provided
            if (! $platform) {
                $userAgent = $request->userAgent() ?? '';
                $platform = self::detectPlatform($userAgent);
            }

            DB::table('core_search_history')->insert([
                'keyword' => trim($keyword),
                'user_ip' => $request->ip(),
                'user_id' => Auth::id(),
                'platform' => $platform,
                'is_operator' => $isOperator ? 1 : 0,
                'from_b2b' => $fromB2B ? 1 : 0,
                'device_type' => self::detectDeviceType($request->userAgent() ?? ''),
                'browser' => self::detectBrowser($request->userAgent() ?? ''),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't break the search functionality
            \Log::error('Failed to record search history: '.$e->getMessage());
        }
    }

    /**
     * Detect platform from user agent
     */
    private static function detectPlatform(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);

        if (str_contains($userAgent, 'iphone') || str_contains($userAgent, 'ipad') || str_contains($userAgent, 'ios')) {
            return 'ios';
        }

        if (str_contains($userAgent, 'android')) {
            return 'android';
        }

        return 'web';
    }

    /**
     * Detect device type from user agent
     */
    private static function detectDeviceType(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);

        if (str_contains($userAgent, 'mobile') || str_contains($userAgent, 'iphone') || str_contains($userAgent, 'android')) {
            return 'mobile';
        }

        if (str_contains($userAgent, 'tablet') || str_contains($userAgent, 'ipad')) {
            return 'tablet';
        }

        return 'desktop';
    }

    /**
     * Detect browser from user agent
     */
    private static function detectBrowser(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);

        if (str_contains($userAgent, 'chrome') && ! str_contains($userAgent, 'edge')) {
            return 'Chrome';
        }
        if (str_contains($userAgent, 'safari') && ! str_contains($userAgent, 'chrome')) {
            return 'Safari';
        }
        if (str_contains($userAgent, 'firefox')) {
            return 'Firefox';
        }
        if (str_contains($userAgent, 'edge')) {
            return 'Edge';
        }
        if (str_contains($userAgent, 'opera') || str_contains($userAgent, 'opr')) {
            return 'Opera';
        }

        return 'Other';
    }
}
