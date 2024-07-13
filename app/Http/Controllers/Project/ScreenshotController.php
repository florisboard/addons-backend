<?php

namespace App\Http\Controllers\Project;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
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
        $this->authorize('updateImages', $project);

        $request->validate([
            'screenshots_path' => ['required', 'array', 'max:5'],
            'screenshots_path.*' => ['bail', 'required', 'string', new FileUpload(['image/png', 'image/jpeg'])],
        ]);

        if ($project->status === StatusEnum::Draft) {
            foreach ($request->input('screenshots_path') as $screenshot) {
                $project->addMediaFromDisk($screenshot)
                    ->toMediaCollection('screenshots');
            }
        } else {
            $changeProposal = $project->latestChangeProposal;
            $changeProposal->update(['data' => [
                ...$changeProposal->data,
                'screenshots_path' => $request->input('screenshots_path'),
            ]]);
        }

        return new JsonResponse(['message' => 'Screenshots has been saved successfully.']);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Project $project, int $media): JsonResponse
    {
        $this->authorize('deleteImages', $project);

        $project->screenshots()
            ->where('id', $media)
            ->first()
            ?->delete();

        return new JsonResponse(['message' => 'Screenshot has been deleted successfully.']);
    }
}
