<?php

namespace App\Enums;

use EmreYarligan\EnumConcern\EnumConcern;
use Filament\Support\Contracts\HasColor;

enum ProjectTypeEnum: int implements HasColor
{
    use EnumConcern;
    case Extension = 1;

    public function getColor(): string
    {
        return 'primary';
    }
}
