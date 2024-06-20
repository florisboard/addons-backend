<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProjectReportController extends Controller
{
    public function store(StoreReportRequest $request, Project $project): JsonResponse
    {
        $project->reports()->create([
            ...$request->validated(),
            'user_id' => Auth::id(),
        ]);

        return new JsonResponse([
            'message' => "You've reported the project $project->id successfully.",
        ], 201);
    }
}
