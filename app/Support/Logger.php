<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;

class Logger
{
    public static function info(string $message, array $context = []): void
    {
        Log::info($message, self::enrich($context));
    }

    public static function error(string $message, array $context = []): void
    {
        Log::error($message, self::enrich($context));
    }

    public static function warning(string $message, array $context = []): void
    {
        Log::warning($message, self::enrich($context));
    }

    private static function enrich(array $context): array
    {
        $requestId = app()->bound('request_id') ? app('request_id') : null;
        return array_merge([
            'request_id' => $requestId,
            'user_id' => auth()->id(),
            'ip' => request()?->ip(),
            'url' => request()?->fullUrl(),
        ], $context);
    }
}
