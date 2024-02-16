<?php

use App\Validations\ValidateReleaseVersionName;

test('passes', function (string $providedVersion, string $previousVersion) {
    expect(ValidateReleaseVersionName::isProvidedVersionNewer($providedVersion, $previousVersion))->toBeTrue();
})->with([
    ['2.0.0', '1.0.0'],
    ['1.0.1', '1.0.0'],
    ['1.56.56', '1.56.55'],
]);

test('failes', function (string $providedVersion, string $previousVersion) {
    expect(ValidateReleaseVersionName::isProvidedVersionNewer($providedVersion, $previousVersion))->toBeFalse();
})->with([
    ['2.0.0', '2.0.0'],
    ['1.0.1', '1.0.2'],
    ['1.0.0', '0.0.1'],
]);
