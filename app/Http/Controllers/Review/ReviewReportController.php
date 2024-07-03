<?php

namespace App\Http\Controllers\Review;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReviewReportController extends Controller
{
    public function store(StoreReportRequest $request, Review $review): JsonResponse
    {
        /** @var Report|null $previousReport */
        $previousReport = $review->reports()->where('user_id', Auth::id())->latest('id')->first();

        if ($previousReport && $previousReport->created_at->gt(now()->subHours(24))) {
            return new JsonResponse([
                'message' => 'You cannot report this review again so soon. Please wait until 24 hours after your last report.',
            ], 429);
        }

        $review->reports()->create([
            ...$request->validated(),
            'user_id' => Auth::id(),
            'status' => StatusEnum::Pending,
        ]);

        return new JsonResponse([
            'message' => "You've reported the review $review->id successfully.",
        ], 201);
    }
}
