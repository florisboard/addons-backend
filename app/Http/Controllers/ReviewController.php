<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Project;
use App\Models\Review;
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

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Review::class);
    }

    /**
     * @return AnonymousResourceCollection<LengthAwarePaginator<ReviewResource>>
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'filter' => ['nullable', 'array'],
            'filter.score' => ['nullable', 'integer', 'between:1,5'],
            'filter.project_id' => ['nullable', 'integer'],
            'filter.user_id' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer'],
            'sort' => ['nullable', 'string', Rule::in('id', '-id')],
        ]);

        $reviews = QueryBuilder::for(Review::class)
            ->allowedFilters([
                AllowedFilter::exact('project_id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('score'),
            ])
            ->allowedSorts('id')
            ->when($request->input('filter.project_id') && Auth::check(), function (Builder $builder) {
                $builder->where('user_id', '!=', Auth::id());
            })
            ->with('user')
            ->fastPaginate(20);

        return ReviewResource::collection($reviews);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(ReviewRequest $request, Project $project): JsonResponse
    {
        $review = $project->reviews()->create([
            ...$request->validated(),
            'user_id' => Auth::id(),
        ]);

        $review->load('user');

        return new JsonResponse(new ReviewResource($review), 201);
    }

    public function show(Review $review): ReviewResource
    {
        $review->load('user');

        return new ReviewResource($review);
    }

    public function update(ReviewRequest $request, Review $review): ReviewResource
    {
        $review->update($request->validated());

        return $this->show($review);
    }

    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return new JsonResponse(['message' => 'Review deleted successfully.']);
    }
}
