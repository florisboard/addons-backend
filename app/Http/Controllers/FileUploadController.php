<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;

class FileUploadController extends Controller
{
    public function __invoke(Request $request): bool|string
    {
        $request->validate([
            'file' => ['required', File::image()->min('1kb')->max('512kb')],
        ]);

        /* @phpstan-ignore-next-line */
        return $request->file('file')->store($this->generatePath());
    }

    public function generatePath(): string
    {
        return 'tmp/'.now()->timestamp.'-'.Str::random(20);
    }
}
