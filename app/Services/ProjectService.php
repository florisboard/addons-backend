<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use App\Models\Release;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectService
{
    /**
     * @return int[]
     */
    public function choosePicksOfTheDayIds(): array
    {
        return Cache::remember('projects.picksOfTheDay.ids', now()->endOfDay(), function () {

            return Project::query()
                ->where('status', StatusEnum::Approved)
                ->inRandomOrder()
                ->take(10)
                ->pluck('id')
                ->toArray();
        });
    }

    public function picksOfTheDay(): AnonymousResourceCollection
    {
        return Cache::remember('projects.picksOfTheDay', now()->addMinutes(5), function () {
            $ids = $this->choosePicksOfTheDayIds();

            $projects = Project::query()
                ->with(['image', 'latestRelease'])
                ->where('status', StatusEnum::Approved)
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
        return Cache::remember('projects.latestReleases', now()->addMinutes(5), function () {
            $latestReleases = Release::query()
                ->select('project_id', DB::raw('MAX(id) as last_release_id'))
                ->groupBy('project_id');

            $projects = Project::query()
                ->with(['image', 'latestRelease'])
                ->where('status', StatusEnum::Approved)
                ->withCount('reviews')
                ->withSum('releases', 'downloads_count')
                ->withAvg('reviews', 'score')
                ->joinSub($latestReleases, 'latest_releases', function (JoinClause $join) {
                    $join->on('projects.id', '=', 'latest_releases.project_id');
                })
                ->orderByDesc('latest_releases.last_release_id')
                ->take(10)
                ->get();

            return ProjectResource::collection($projects);
        });
    }

    public function latestProjects(): AnonymousResourceCollection
    {
        return Cache::remember('projects.latestProjects', now()->addMinutes(5), function () {
            $projects = Project::query()
                ->with(['image', 'latestRelease'])
                ->where('status', StatusEnum::Approved)
                ->withCount('reviews')
                ->withSum('releases', 'downloads_count')
                ->withAvg('reviews', 'score')
                ->orderByDesc('id')
                ->take(10)
                ->get();

            return ProjectResource::collection($projects);
        });
    }

    public function recommended(): AnonymousResourceCollection
    {
        return Cache::remember('projects.recommended', now()->addMinutes(5), function () {
            $projects = Project::query()
                ->with(['image', 'latestRelease'])
                ->where('status', StatusEnum::Approved)
                ->withCount('reviews')
                ->withSum('releases', 'downloads_count')
                ->withAvg('reviews', 'score')
                ->where('is_recommended', true)
                ->take(10)
                ->get();

            return ProjectResource::collection($projects);
        });
    }

    public function isMaintainer(int $userId, int|Project $project): bool
    {
        if (is_int($project)) {
            $project = Project::find($project);
        }

        if (! $project) {
            return false;
        }

        if ($project->user_id === $userId) {
            return true;
        }

        return $project->maintainers()->where('user_id', $userId)->exists();
    }

    public function convertToPackageName(string $name, string $domain): string
    {
        return Str::of($domain)
            ->explode('.')
            ->reverse()
            ->push($name)
            ->implode('.');
    }
}
