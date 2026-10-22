<?php

namespace App\Filament\Forms\Layouts;

use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class BasicForm
{
    /**
     * @param  array<Component>  $components
     * @param  array<Component>  $sideComponents
     */
    public static function make(Schema $schema, array $components = [], array $sideComponents = []): Schema
    {

        return ComplexForm::make($schema, [BasicSection::make($components)], $sideComponents)
            ->columns($schema->getRecord() ? 3 : 2);
    }
}
