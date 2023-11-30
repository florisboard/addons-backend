<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'filter' => ['nullable', 'array'],
            'filter.name' => ['nullable', 'string'],
            'filter.category_id' => ['nullable', 'numeric'],
            'filter.user_id' => ['nullable', 'numeric'],
            'filter.package_name' => ['nullable', 'string'],
            'filter.is_recommended' => ['nullable', 'boolean'],
            // Fields : name,package_name,created_at
            'sort' => ['nullable', 'string'],
            // Fields : user,maintainers,screenshots,category
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
            ->allowedIncludes(['user', 'maintainers', 'screenshots', 'category'])
            ->allowedSorts(['name', 'package_name', 'created_at'])
            ->with(['image', 'latestRelease'])
            ->withAvg('reviews', 'score')
            ->fastPaginate(20);

        return ProjectResource::collection($projects);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Project $project): ProjectResource
    {
        $project->load(['image', 'maintainers', 'latestRelease']);
        $project->loadAvg('reviews', 'score');
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

    public function update(Request $request, Project $project)
    {
        //
    }

    public function destroy(Project $project)
    {
        //
    }
}
