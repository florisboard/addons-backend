<?php

namespace App\Http\Controllers;

use App\Http\Requests\Release\StoreReleaseRequest;
use App\Http\Resources\Release\ReleaseFullResource;
use App\Models\Project;
use App\Models\Release;
use App\Services\FilesystemService;
use App\Services\ReleaseService;
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
    public function __construct(private readonly FilesystemService $filesystemService, private readonly ReleaseService $releaseService)
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

    /**
     * @throws \Throwable
     */
    public function store(StoreReleaseRequest $request, Project $project): JsonResponse
    {
        $previousVersionCode = $project->latestRelease?->version_code;
        $versionCode = $previousVersionCode ? $previousVersionCode + 1 : 1;

        /** @var Release $release */
        $release = $project->releases()->create([
            ...$request->safe()->except('file'),
            'user_id' => Auth::id(),
            'version_code' => $versionCode,
        ]);

        $filePath = $request->input('file');
        $fileExtensionName = pathinfo($filePath, PATHINFO_EXTENSION);

        $tempDirPath = $this->filesystemService->createTempDirectory('projects', $project->id);
        try {
            file_put_contents("$tempDirPath/file.$fileExtensionName", file_get_contents($this->filesystemService->getStorageUrl($filePath)));
            $this->filesystemService->extractZipFile($tempDirPath, "file.$fileExtensionName");

            $result = $this->releaseService->parseExtensionJson($tempDirPath);
            $result['$'] = $project->package_name;
            $result['meta']['version'] = $request->input('version_name');
            $this->releaseService->replaceExtensionJson($tempDirPath, $result);

            exec("cd $tempDirPath/extracted && zip -r ../repacked.$fileExtensionName ./*");

            $release
                ->addMedia(file_get_contents("$tempDirPath/repacked.$fileExtensionName"))
                ->toMediaCollection('file');

        } catch (\RuntimeException $e) {
            $this->filesystemService->deleteDirectory($tempDirPath);
            throw $e;
        }

        $this->filesystemService->deleteDirectory($tempDirPath);

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
