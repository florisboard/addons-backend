<?php

namespace App\Enums;

use EmreYarligan\EnumConcern\EnumConcern;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ReportTypeEnum: string implements HasColor, HasLabel
{
    use EnumConcern;
    case Spam = 'SPAM';

    case Misinformation = 'MISINFORMATION';

    case Harassment = 'HARASSMENT';

    case HateSpeech = 'HATE_SPEACH';

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
