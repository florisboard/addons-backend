<?php

namespace App\Http\Controllers;

use App\Http\Resources\Release\ReleaseFullResource;
use App\Models\Release;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
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
        ]);

        $projects = QueryBuilder::for(Release::class)
            ->allowedFilters([
                AllowedFilter::exact('project_id'),
            ])
            ->with('user')
            ->fastPaginate(20);

        return ReleaseFullResource::collection($projects);
    }
}
