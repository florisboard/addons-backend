<?php

namespace App\Validations;

use App\Models\Project;
use App\Services\FilesystemService;
use App\Services\ReleaseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
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
        file_put_contents("$tempDirPath/file.$fileExtensionName", Storage::get($filePath));
        $filesystemService->extractZipFile($tempDirPath, "file.$fileExtensionName");

        $result = $releaseService->parseExtensionJson($tempDirPath);
        $filesystemService->deleteDirectory($tempDirPath);

        if (!$result) {
            $validator->errors()->add('file_path', "The uploaded file doesn't have extension.json");

            return;
        }

        ray($result);
        $jsonValidator = \Illuminate\Support\Facades\Validator::make($result, [
            '$' => ['required', 'string', Rule::in($project->type->getValidationId())],
            'meta.id' => ['required', 'string', Rule::in($project->package_name)],
            'meta.version' => ['required', 'string', Rule::in($validator->getData()['version_name'])],
            'meta.title' => ['required', 'string', 'min:3'],
            'meta.license' => ['required', 'string', 'min:1'],
            'meta.maintainers' => ['required', 'array', 'min:1'],
            'meta.maintainers.*' => ['required', 'string', 'min:1'],
        ], [
            '$.in' => "The provided file ID was :input but it should be {$project->type->getValidationId()}.",
            'meta.id.in' => "The provided file ID was :input but it should be {$project->package_name}.",
            'meta.version.in' => "The provided version was :input but it should be {$validator->getData()['version_name']}.",
        ]);

        if ($jsonValidator->fails()) {
            foreach ($jsonValidator->errors()->all() as $error) {
                $validator->errors()->add('file_path', $error);
            }
        }
    }
}
