<?php

namespace App\Enums;

use EmreYarligan\EnumConcern\EnumConcern;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProjectTypeEnum: string implements HasColor, HasLabel
{
    use EnumConcern;
    case Extension = 'EXTENSION';

    public function getLabel(): string
    {
        return match ($this) {
            self::Extension => 'Extension',
        };
    }

    public function getColor(): string
    {
        return 'primary';
    }
}
