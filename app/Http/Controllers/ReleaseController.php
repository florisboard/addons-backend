<?php

namespace App\Http\Controllers;

use App\Http\Requests\Release\StoreReleaseRequest;
use App\Http\Resources\Release\ReleaseFullResource;
use App\Models\Project;
use App\Models\Release;
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
    public function __construct()
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

        $releases = QueryBuilder::for(Release::class)
            ->allowedFilters([
                AllowedFilter::exact('project_id'),
            ])
            ->allowedSorts('id')
            ->with('user')
            ->fastPaginate(20);

        return ReleaseFullResource::collection($releases);
    }

    public function store(StoreReleaseRequest $request, Project $project): JsonResponse
    {
        $previousVersionCode = $project->latestRelease?->version_code;
        $versionCode = $previousVersionCode ? $previousVersionCode + 1 : 1;

        $release = $project->releases()->create([
            ...$request->safe()->except('file'),
            'user_id' => Auth::id(),
            'version_code' => $versionCode,
        ]);

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

    public function download(Release $release): JsonResponse
    {
        Release::where('id', $release->id)->increment('downloads_count');

        return new JsonResponse(['link' => $release->file->getFullUrl()]);
    }
}
