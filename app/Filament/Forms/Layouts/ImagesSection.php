<?php

namespace App\Filament\Forms\Layouts;

use Filament\Forms;
use Filament\Forms\Components\Section;

class ImagesSection
{
    /**
     * @param  array<Forms\Components\Component>  $components
     * @return Section
     */
    public static function make(array $components = []): Forms\Components\Section
    {
        return Forms\Components\Section::make('Images')
            ->schema($components);
    }
}
