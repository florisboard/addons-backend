<?php

namespace App\Services;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    public function top(): AnonymousResourceCollection
    {
        return Cache::remember('categories.top', now()->addMinutes(5), function () {
            $projects = Category::query()
                ->ordered()
                ->where('is_top', true)
                ->get();

            return CategoryResource::collection($projects);
        });
    }
}
