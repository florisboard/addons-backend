<?php

namespace App\Filament\Forms\Layouts;

use Filament\Forms;
use Filament\Forms\Form;

class ComplexForm
{
    /**
     * @param  array<Forms\Components\Component>  $components
     * @param  array<Forms\Components\Component>  $sideComponents
     */
    public static function make(Form $form, array $components = [], array $sideComponents = []): Form
    {
        array_unshift($sideComponents, TimestampsSection::make());

        return $form->schema([MainGroup::make($components), SideGroup::make($sideComponents)])
            ->columns(3);
    }
}
