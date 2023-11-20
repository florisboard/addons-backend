<?php

namespace App\Filament\Forms\Components;

use Filament\Forms;

class ImageInput
{
    public static function make(string $name, bool $isMultiple = false): Forms\Components\SpatieMediaLibraryFileUpload
    {
        return Forms\Components\SpatieMediaLibraryFileUpload::make($name)
            ->collection($name)
            ->image()
            ->imageEditor()
            ->multiple($isMultiple)
            ->reorderable($isMultiple)
            ->openable()
            ->downloadable();
    }
}
