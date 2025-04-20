<?php

namespace App\Http\Controllers;

use App\Http\Resources\CollectionResource;
use App\Models\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CollectionController extends Controller
{
    /**
     * @return AnonymousResourceCollection<LengthAwarePaginator<CollectionResource>>
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'filter' => ['nullable', 'array'],
            'filter.title' => ['nullable', 'string'],
            'filter.user_id' => ['nullable', 'numeric'],
            'page' => ['nullable', 'integer'],
        ]);

        $collections = QueryBuilder::for(Collection::class)
            ->allowedFilters([
                AllowedFilter::exact('user_id'),
                AllowedFilter::partial('title'),
            ])
            ->with(['projects' => function (BelongsToMany $builder) {
                return $builder->with('image')->take(3);
            }])
            ->withCount(['projects'])
            ->paginate(20);

        return CollectionResource::collection($collections);
    }

    public function store(Request $request): void
    {
        //
    }

    public function show(Collection $collection): void
    {
        //
    }

    public function update(Request $request, Collection $collection): void
    {
        //
    }

    public function destroy(Collection $collection): void
    {
        //
    }
}
