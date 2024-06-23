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
    public function generateVerificationCode(): string
    {
        return (string) random_int(static::MIN_VERIFICATION_CODE, static::MAX_VERIFICATION_CODE);
    }

    public function generateVerificationText(string $code): string
    {
        return "florisboard-addons-verification=$code";
    }

    public function isInExcludedDomains(string $name): bool
    {
        return collect(static::reservedDomains)->some(fn ($domain) => Str::endsWith($name, $domain));
    }

    public function hasVerificationText(string $domain, string $verificationCode): bool
    {
        try {
            /** @var array[] $records */
            $records = dns_get_record($domain, DNS_TXT);

            return collect($records)->some(function (array $record) use ($verificationCode) {
                return $record['host'] && $record['txt'] === static::generateVerificationText($verificationCode);
            });
        } catch (\Exception $e) {
            return false;
        }
    }
}
