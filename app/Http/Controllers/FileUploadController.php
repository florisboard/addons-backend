<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;

class FileUploadController extends Controller
{
    public function __invoke(Request $request): bool|string
    {
        $request->validate([
            'file' => ['required', File::default()->min('1kb')->max('512kb')],
        ]);

        /* @phpstan-ignore-next-line */
        return $request->file('file')->storeAs($this->generatePath($request->file('file')->getClientOriginalExtension()));
    }

    public function generatePath(string $extension): string
    {
        return sprintf(
            'tmp/%s-%s-%s.%s',
            Auth::id(),
            now()->timestamp,
            Str::random(20),
            Str::remove('.', $extension)
        );
    }
}
