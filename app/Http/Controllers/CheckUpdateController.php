<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Release\StoreReleaseRequest;
use App\Http\Resources\CheckUpdateResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckUpdateController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): array
    {
        $request->validate([
            'projects' => ['required', 'array', 'min:1'],
            'projects.*.package_name' => ['required', 'string', 'min:3', 'max:255', 'regex:'.StoreProjectRequest::$packageNameRegex],
            'projects.*.version_name' => ['required', 'string', 'regex:'.StoreReleaseRequest::$versionNameRegex],
        ]);

        $packageNames = collect($request->input('projects'))->pluck('package_name');
        $projects = Project::query()
            ->where('status', StatusEnum::Approved)
            ->whereIn('package_name', $packageNames)
            ->with('latestApprovedRelease.user')
            ->get();

        return [
            /** @var CheckUpdateResource[] */
            'data' => CheckUpdateResource::collection($projects),
        ];
    }
}
