<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReviewReportController extends Controller
{
    public function store(StoreReportRequest $request, Review $review): JsonResponse
    {
        $review->reports()->create([
            ...$request->validated(),
            'user_id' => Auth::id(),
        ]);

        return new JsonResponse([
            'message' => "You've reported the review $review->id successfully.",
        ], 201);
    }
}
