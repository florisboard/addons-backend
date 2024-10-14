<?php

use App\Validations\ValidateReleaseVersionName;

test('passes', function (string $providedVersion, string $previousVersion) {
    expect(ValidateReleaseVersionName::isProvidedVersionNewer($providedVersion, $previousVersion))->toBeTrue();
})->with([
    ['2.0.0', '1.0.0'],
    ['1.0.1', '1.0.0'],
    ['4.0.0', '3.0.2'],
    ['1.56.56', '1.56.55'],
    ['1.0.0', '0.0.1'],
]);

test('failes', function (string $providedVersion, string $previousVersion) {
    expect(ValidateReleaseVersionName::isProvidedVersionNewer($providedVersion, $previousVersion))->toBeFalse();
})->with([
    ['2.0.0', '2.0.0'],
    ['1.0.1', '1.0.2'],
    ['1.2.3', '1.3.5'],
]);
