<?php

namespace App\Filament\Forms\Layouts;

use Filament\Forms;
use Filament\Forms\Form;

class BasicForm
{
    /**
     * @param  array<Forms\Components\Component>  $components
     * @param  array<Forms\Components\Component>  $sideComponents
     */
    public static function make(Form $form, array $components = [], array $sideComponents = []): Form
    {

        return ComplexForm::make($form, [BasicSection::make($components)], $sideComponents)
            ->columns($form->getRecord() ? 3 : 2);
    }
}
