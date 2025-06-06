<?php

namespace App\Validations;

use App\Models\Project;
use Illuminate\Validation\Validator;

class ValidateReleaseVersionName
{
    public static function isProvidedVersionNewer(string $providedVersion, string $previousVersion): bool
    {
        if ($providedVersion === $previousVersion) {
            return false;
        }

        $providedVersionParts = array_map('intval', explode('.', $providedVersion));
        $previousVersionParts = array_map('intval', explode('.', $previousVersion));

        foreach ($providedVersionParts as $index => $part) {
            if ($part > $previousVersionParts[$index]) {
                return true;
            }

            if ($part < $previousVersionParts[$index]) {
                return false;
            }
        }

        return true;
    }

    public function __invoke(Validator $validator): void
    {

        /** @var Project $project */
        $project = request()->route('project');

        // It's the first release
        if (! $project->latestApprovedRelease) {
            return;
        }

        $previousVersionName = $project->latestApprovedRelease->version_name;

        $isValid = static::isProvidedVersionNewer($validator->getData()['version_name'], $previousVersionName);

        $validator->errors()->addIf(! $isValid, 'version_name', "The new release should be bigger than previous version which is $previousVersionName");
    }
}
