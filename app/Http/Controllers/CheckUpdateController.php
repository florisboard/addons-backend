<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Release\StoreReleaseRequest;
use App\Http\Resources\CheckUpdateResource;
use App\Models\Project;
use App\Models\Scopes\ActiveScope;
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
            'projects' => ['required', 'array', 'distinct'],
            'projects.*' => ['required', 'string', 'max:255', 'regex:'.StoreProjectRequest::$packageNameRegex],
            'versions' => ['required', 'array'],
            'versions.*' => ['required', 'string', 'regex:'.StoreReleaseRequest::$versionNameRegex],
        ]);

        if (count($request->input('projects')) !== count($request->input('versions'))) {
            throw ValidationException::withMessages(['message' => 'The length of array versions and projects must match.']);
        }

        $projects = Project::query()
            ->withGlobalScope('active', new ActiveScope)
            ->whereIn('package_name', $request->input('projects'))
            ->with('latestRelease.user')
            ->get();

        return [
            'data' => CheckUpdateResource::collection($projects),
        ];
    }
}
