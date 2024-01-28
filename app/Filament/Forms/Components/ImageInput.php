<?php

namespace App\Filament\Forms\Components;

use Filament\Forms;

class ImageInput
{
    public static function make(string $name, bool $isMultiple = false): Forms\Components\SpatieMediaLibraryFileUpload
    {
        return FileInput::make($name, $isMultiple)
            ->image()
            ->imageEditor();
    }
}
