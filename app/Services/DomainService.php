<?php

namespace App\Services;

use Illuminate\Support\Str;
use Random\RandomException;

class DomainService
{
    public const MIN_VERIFICATION_CODE = 100000;

    public const MAX_VERIFICATION_CODE = 999999;

    public const REGEX = '/^(?!-)[A-Za-z0-9-]+([\\-\\.]{1}[a-z0-9]+)*\\.[A-Za-z]{2,6}$/';

    public const reservedDomains = [
        'github.io',
        'florisboard.org',
    ];

    /**
     * @throws RandomException
     */
    public static function generateVerificationCode(): int
    {
        return random_int(self::MIN_VERIFICATION_CODE, self::MAX_VERIFICATION_CODE);
    }

    public static function generateVerificationText(int $code): string
    {
        return "florisboard-addons-verification-$code";
    }

    public static function isInExcludedDomains(string $name): bool
    {
        return collect(self::reservedDomains)->some(fn ($domain) => Str::endsWith($name,$domain));
    }
}
