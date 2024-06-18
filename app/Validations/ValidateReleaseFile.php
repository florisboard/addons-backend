<?php

namespace App\Validations;

use App\Models\Project;
use App\Services\FilesystemService;
use App\Services\ReleaseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;

class ValidateReleaseFile
{
    /**
     * @throws \Throwable
     * @throws \JsonException
     */
    public function __invoke(Validator $validator): void
    {

        // It's already has a validation error for file_path
        // so we can't validate the release file
        if ($validator->errors()->has('file_path')) {
            return;
        }

        /* @var Project $project */
        $project = request()->route('project');
        $filesystemService = app(FilesystemService::class);
        $releaseService = app(ReleaseService::class);

        $filePath = $validator->getData()['file_path'];
        $fileExtensionName = pathinfo($filePath, PATHINFO_EXTENSION);

        $tempDirPath = $filesystemService->createTempDirectory('projects', $project->id);
        file_put_contents("$tempDirPath/file.$fileExtensionName", Storage::get("$tempDirPath/file.$fileExtensionName"));
        $filesystemService->extractZipFile($tempDirPath, "file.$fileExtensionName");

        $result = $releaseService->parseExtensionJson($tempDirPath);

        if (! $result) {
            $validator->errors()->add('file_path', "The uploaded file doesn't have extension.json");

            return;
        }

        $filesystemService->deleteDirectory($tempDirPath);
        $filePackageName = data_get($result, '$');
        $fileVersionName = data_get($result, 'meta.version');

        $validator->errors()->addIf($filePackageName !== $project->package_name, 'file_path', "The file package_name doesn't match the project package name. The file package name is $filePackageName but the project package name is {$project->package_name} ");
        $validator->errors()->addIf($fileVersionName !== $validator->getData()['version_name'], 'file_path', "The file version_name doesn't match the project package name. The file package name is $fileVersionName but the project package name is {$validator->getData()['version_name']} ");
    }
}
