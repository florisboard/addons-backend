<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Models\Project;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProjectReportController extends Controller
{
    public function store(StoreReportRequest $request, Project $project): JsonResponse
    {
        /** @var Report|null $previousReport */
        $previousReport = $project->reports()->where('user_id', Auth::id())->latest('id')->first();

        if ($previousReport && $previousReport->created_at->gt(now()->subHours(24))) {
            return new JsonResponse([
                'message' => 'You cannot report this project again so soon. Please wait until 24 hours after your last report.',
            ], 429);
        }

        $project->reports()->create([
            ...$request->validated(),
            'user_id' => Auth::id(),
        ]);

        return new JsonResponse([
            'message' => "You've reported the project $project->id successfully.",
        ], 201);
    }
}
