<?php

namespace App\Enums;

use EmreYarligan\EnumConcern\EnumConcern;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ReportTypeEnum: int implements HasColor, HasLabel
{
    use EnumConcern;
    case Spam = 1;

    case Misinformation = 2;

    case Harassment = 3;

    case HateSpeech = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::Spam => 'Spam',
            self::Misinformation => 'MisInformation',
            self::Harassment => 'Harassment',
            self::HateSpeech => 'Hate Speech',
        };
    }

    public function getColor(): string
    {
        return 'primary';
    }
}
