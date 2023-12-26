<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class);
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'filter' => ['nullable', 'array'],
            'filter.name' => ['nullable', 'string'],
            'filter.category_id' => ['nullable', 'numeric'],
            'filter.user_id' => ['nullable', 'numeric'],
            'filter.package_name' => ['nullable', 'string'],
            'filter.is_recommended' => ['nullable', 'boolean'],
            // Fields : name,package_name,id
            'sort' => ['nullable', 'string'],
            // Fields : user,category
            'include' => ['nullable', 'string'],

        ]);

        $projects = QueryBuilder::for(Project::class)
            ->allowedFilters([
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('package_name'),
                AllowedFilter::exact('is_recommended'),
            ])
            ->allowedIncludes(['user', 'category'])
            ->allowedSorts(['name', 'package_name', 'id'])
            ->with(['image', 'latestRelease'])
            ->withCount('reviews')
            ->withSum('releases', 'downloads_count')
            ->withAvg('reviews', 'score')
            ->fastPaginate(20);

        return ProjectResource::collection($projects);
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $project = Project::create([
            ...$request->safe()->except(['maintainers']),
            'user_id' => Auth::id(),
        ]);

        $project->maintainers()->attach($request->maintainers);

        return new JsonResponse($this->show($project), 201);
    }

    public function show(Project $project): ProjectResource
    {
        $project->load(['image', 'maintainers', 'latestRelease', 'category']);
        $project->loadAvg('reviews', 'score');
        $project->loadSum('releases', 'downloads_count');
        $project->loadCount([
            'reviews',
            'reviews as one_reviews_count' => function (Builder $query) {
                $query->where('score', 1);
            },
            'reviews as two_reviews_count' => function (Builder $query) {
                $query->where('score', 2);
            },
            'reviews as three_reviews_count' => function (Builder $query) {
                $query->where('score', 3);
            },
            'reviews as four_reviews_count' => function (Builder $query) {
                $query->where('score', 4);
            },
            'reviews as five_reviews_count' => function (Builder $query) {
                $query->where('score', 5);
            },
        ]);

        return new ProjectResource($project);
    }

    public function update(ProjectRequest $request, Project $project): ProjectResource
    {
        $project->update($request->safe()->except(['maintainers']));

        $project->maintainers()->sync($request->maintainers);

        return $this->show($project);
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return new JsonResponse(['message' => 'Project has been deleted successfully.']);
    }
}
