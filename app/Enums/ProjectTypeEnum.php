<?php

namespace App\Enums;

use EmreYarligan\EnumConcern\EnumConcern;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum ProjectTypeEnum: string implements HasColor, HasLabel
{
    use EnumConcern;

    case Theme = 'THEME';

    public function getLabel(): string
    {
        return Str::of($this->name)->ucsplit()->join(' ');
    }

    public function getColor(): string
    {
        return 'primary';
    }

    public function getValidationId(): string
    {
        return match ($this) {
            self::Theme => 'ime.extension.theme'
        };
    }
}
