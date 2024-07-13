<?php

namespace App\Enums;

use EmreYarligan\EnumConcern\EnumConcern;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum StatusEnum: string implements HasColor, HasLabel
{
    use EnumConcern;

    case Draft = 'DRAFT';

    case UnderReview = 'UNDER_REVIEW';

    case Approved = 'APPROVED';

    case Rejected = 'REJECTED';

    public function getLabel(): string
    {
        return Str::of($this->name)->ucsplit()->join(' ');
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft, self::UnderReview => 'warning',
            self::Approved => 'primary',
            self::Rejected => 'danger',
        };
    }
}
