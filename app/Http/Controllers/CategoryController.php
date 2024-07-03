<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Scopes\ActiveScope;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    /**
     * @return AnonymousResourceCollection<LengthAwarePaginator<CategoryResource>>
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'filter' => ['nullable', 'array'],
            'filter.title' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
        ]);

        $categories = QueryBuilder::for(Category::class)
            ->allowedFilters([
                AllowedFilter::partial('title'),
            ])
            ->withGlobalScope('active', new ActiveScope)
            ->fastPaginate(20);

        return CategoryResource::collection($categories);
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }
}
