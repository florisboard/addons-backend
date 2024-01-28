<?php

namespace App\Http\Controllers;

use App\Http\Resources\Release\ReleaseFullResource;
use App\Models\Release;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReleaseController extends Controller
{
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

        $projects = QueryBuilder::for(Release::class)
            ->allowedFilters([
                AllowedFilter::exact('project_id'),
            ])
            ->allowedSorts('id')
            ->with('user')
            ->fastPaginate(20);

        return ReleaseFullResource::collection($projects);
    }

    public function download(Release $release): JsonResponse
    {
        Release::where('id', $release->id)->increment('downloads_count');

        return new JsonResponse(['link' => $release->file->getFullUrl()]);
    }
}
