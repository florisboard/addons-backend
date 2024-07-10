<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\Release\StoreReleaseRequest;
use App\Http\Resources\Release\ReleaseFullResource;
use App\Models\Project;
use App\Models\Release;
use App\Services\ProjectService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReleaseController extends Controller
{
    public function __construct(private readonly ProjectService $projectService)
    {
        $this->authorizeResource(Release::class);
    }

    /**
     * @return AnonymousResourceCollection<LengthAwarePaginator<ReleaseFullResource>>
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'filter' => ['nullable', 'array'],
            'filter.project_id' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer'],
            'sort' => ['nullable', 'string', Rule::in('id', '-id')],
        ]);

        $isMaintainer = Auth::check()
            && $request->filled('filter.project_id')
            && $this->projectService->isMaintainer(Auth::id(), $request->input('filter.project_id'));

        $releases = QueryBuilder::for(Release::class)
            ->allowedFilters([
                AllowedFilter::exact('project_id'),
            ])
            ->allowedSorts('id')
            ->unless($isMaintainer, function (Builder $builder) {
                return $builder->where('status', StatusEnum::Approved);
            })
            ->with('user')
            ->fastPaginate(20);

        return ReleaseFullResource::collection($releases);
    }

    /**
     * @throws \Throwable
     */
    public function store(StoreReleaseRequest $request, Project $project): JsonResponse
    {
        $previousVersionCode = $project->latestRelease?->version_code;
        $versionCode = $previousVersionCode ? $previousVersionCode + 1 : 1;

        /** @var Release $release */
        $release = $project->releases()->create([
            ...$request->safe()->except('file_path'),
            'user_id' => Auth::id(),
            'version_code' => $versionCode,
            'status' => StatusEnum::Pending,
        ]);

        $release->addMediaFromDisk($request->input('file_path'))->toMediaCollection('file');

        return new JsonResponse(new ReleaseFullResource($release), 201);
    }

    public function update(Request $request, Release $release): ReleaseFullResource
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'min:3', 'max:1024'],
        ]);

        $release->update($validated);

        return new ReleaseFullResource($release);
    }

    /**
     * @throws AuthorizationException
     */
    public function download(Release $release): JsonResponse
    {
        $this->authorize('view', $release);

        Release::where('id', $release->id)->increment('downloads_count');

        return new JsonResponse(['link' => $release->file->getFullUrl()]);
    }
}
