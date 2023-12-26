<?php

namespace App\Services;

use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use App\Models\Release;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    /**
     * @return int[]
     */
    public function choosePicksOfTheDayIds(): array
    {
        return Cache::tags(['projects'])->remember('projects.picksOfTheDay.ids', now()->endOfDay(), function () {
            return Project::query()
                ->inRandomOrder()
                ->take(20)
                ->get('id')
                ->pluck('id')
                ->toArray();
        });
    }

    public function picksOfTheDay(): AnonymousResourceCollection
    {
        return Cache::tags(['projects'])->remember('projects.picksOfTheDay', now()->addMinutes(5), function () {
            $ids = $this->choosePicksOfTheDayIds();

            $projects = Project::query()
                ->with(['image', 'latestRelease'])
                ->withCount('reviews')
                ->withSum('releases', 'downloads_count')
                ->withAvg('reviews', 'score')
                ->whereIn('id', $ids)
                ->get();

            return ProjectResource::collection($projects);
        });
    }

    public function latestReleases(): AnonymousResourceCollection
    {
        return Cache::tags(['projects'])->remember('projects.latestReleases', now()->addMinutes(5), function () {
            $latestReleases = Release::query()
                ->select('project_id', DB::raw('MAX(id) as last_release_id'))
                ->groupBy('project_id');

            $projects = Project::query()
                ->with(['image', 'latestRelease'])
                ->withCount('reviews')
                ->withSum('releases', 'downloads_count')
                ->withAvg('reviews', 'score')
                ->joinSub($latestReleases, 'latest_releases', function (JoinClause $join) {
                    $join->on('projects.id', '=', 'latest_releases.project_id');
                })
                ->orderByDesc('latest_releases.last_release_id')
                ->take(20)
                ->get();

            return ProjectResource::collection($projects);
        });
    }

    public function latestProjects(): AnonymousResourceCollection
    {
        return Cache::tags(['projects'])->remember('projects.latestProjects', now()->addMinutes(5), function () {
            $projects = Project::query()
                ->with(['image', 'latestRelease'])
                ->withCount('reviews')
                ->withSum('releases', 'downloads_count')
                ->withAvg('reviews', 'score')
                ->orderByDesc('id')
                ->take(20)
                ->get();

            return ProjectResource::collection($projects);
        });
    }

    public function recommended(): AnonymousResourceCollection
    {
        return Cache::tags(['projects'])->remember('projects.recommended', now()->addMinutes(5), function () {
            $projects = Project::query()
                ->with(['image', 'latestRelease'])
                ->withCount('reviews')
                ->withSum('releases', 'downloads_count')
                ->withAvg('reviews', 'score')
                ->where('is_recommended', true)
                ->take(20)
                ->get();

            return ProjectResource::collection($projects);
        });
    }
}
