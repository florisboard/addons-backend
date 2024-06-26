<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\Project\ProjectResource;
use App\Services\CategoryService;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        private readonly ProjectService $projectService,
        private readonly CategoryService $categoryService
    ) {
    }

    /**
     * @return mixed[]
     */
    public function __invoke(Request $request): array
    {
        return [
            /** @var CategoryResource[] */
            'top_categories' => $this->categoryService->top(),
            /** @var ProjectResource[] */
            'picks_of_the_day' => $this->projectService->picksOfTheDay(),
            /** @var ProjectResource[] */
            'latest_releases' => $this->projectService->latestReleases(),
            /** @var ProjectResource[] */
            'latest_projects' => $this->projectService->latestProjects(),
            /** @var ProjectResource[] */
            'recommended' => $this->projectService->recommended(),
        ];
    }
}
