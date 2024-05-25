<?php

use App\Services\DomainService;

describe('Excluded Domain', function () {
    test('passes', function (string $domain) {
        expect(app(DomainService::class)->isInExcludedDomains($domain))->toBeFalse();
    })->with(['test.com', 'hello.world.org']);

    test('fails', function (string $domain) {
        expect(app(DomainService::class)->isInExcludedDomains($domain))->toBeTrue();
    })->with(['username.github.io', 'sub.florisboard.org']);
});
