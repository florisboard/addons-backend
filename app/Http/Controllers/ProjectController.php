<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\Project\ProjectFullResource;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class);
    }

    /**
     * @return AnonymousResourceCollection<LengthAwarePaginator<ProjectResource>>
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'filter' => ['nullable', 'array'],
            'filter.name' => ['nullable', 'string'],
            'filter.category_id' => ['nullable', 'numeric'],
            'filter.user_id' => ['nullable', 'numeric'],
            'filter.package_name' => ['nullable', 'string'],
            'filter.is_recommended' => ['nullable', 'boolean'],
            'page' => ['nullable', 'integer'],
            'sort' => ['nullable', 'string', Rule::in('package_name', '-package_name', 'name', '-name', 'id', '-id')],
            'include' => ['nullable', 'string', Rule::in('user', 'category')],
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
            ->when($request->input('filter.user_id') != Auth::id(), function (Builder $builder) {
                $builder->withGlobalScope('active', new ActiveScope);
            })
            ->withCount('reviews')
            ->withSum('releases', 'downloads_count')
            ->withAvg('reviews', 'score')
            ->fastPaginate(20);

        return ProjectResource::collection($projects);
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(ProjectRequest $request): JsonResponse
    {
        $project = Project::create([
            ...$request->safe()->except(['maintainers', 'image_path', 'screenshots_path']),
            'user_id' => Auth::id(),
        ]);

        $project->addMediaFromDisk($request->image_path)->toMediaCollection('image');

        $project->maintainers()->attach($request->maintainers);

        return new JsonResponse($this->show($project), 201);
    }

    public function show(Project $project): ProjectFullResource
    {
        $project->load([
            'image',
            'maintainers',
            'latestRelease',
            'category',
            'user',
            'userReview.user',
            'reviews' => fn (HasMany $builder) => $builder->with('user')->take(10),
        ]);
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

        return new ProjectFullResource($project);
    }

    public function update(ProjectRequest $request, Project $project): ProjectFullResource
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
