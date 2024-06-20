<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Rules\FileUpload;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ProjectImageController extends Controller
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
            'image_path' => ['bail', 'required', 'string', new FileUpload(['image/png', 'image/jpeg'])],
        ]);

        $project
            ->addMediaFromDisk($request->input('image_path'))
            ->toMediaCollection('image');

        return new JsonResponse(['message' => 'Image has been saved successfully.']);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $project->image?->delete();

        return new JsonResponse(['message' => 'Image has been deleted successfully.']);
    }
}
