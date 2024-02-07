<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Project;
use App\Rules\FileUpload;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ScreenshotController extends Controller
{
    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws AuthorizationException
     */
    public function store(Request $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $request->validate([
            'screenshots' => ['required', 'array', 'max:5'],
            'screenshots.*' => ['bail', 'required', 'string', new FileUpload(['image/png', 'image/jpeg'])],
        ]);

        foreach ($request->input('screenshots') as $screenshot) {
            $project->addMediaFromDisk($screenshot)
                ->toMediaCollection('screenshots');
        }

        return new JsonResponse(['message' => 'Screenshots has been saved successfully.']);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Project $project, $media): JsonResponse
    {
        $this->authorize('update', $project);

        $project->screenshots()
            ->where('id', $media)
            ->first()
            ?->delete();

        return new JsonResponse(['message' => 'Screenshot has been deleted successfully.']);
    }
}
