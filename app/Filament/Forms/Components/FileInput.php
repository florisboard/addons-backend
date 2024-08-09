<?php

namespace App\Filament\Forms\Components;

use Filament\Forms;

class FileInput
{
    public static function make(string $name, bool $isMultiple = false): Forms\Components\SpatieMediaLibraryFileUpload
    {
        return Forms\Components\SpatieMediaLibraryFileUpload::make($name)
            ->disk(env('FILESYSTEM_DISK'))
            ->collection($name)
            ->multiple($isMultiple)
            ->reorderable($isMultiple)
            ->openable()
            ->downloadable();
    }
}
