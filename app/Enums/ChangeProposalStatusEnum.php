<?php

namespace App\Enums;

use EmreYarligan\EnumConcern\EnumConcern;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum ChangeProposalStatusEnum: string implements HasColor, HasLabel
{
    use EnumConcern;

    case Pending = 'PENDING';

    case Approved = 'APPROVED';

    case Rejected = 'REJECTED';

    public function getLabel(): string
    {
        return Str::of($this->name)->ucsplit()->join(' ');
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Approved => 'primary',
            self::Rejected => 'danger',
            self::Pending => 'warning',
        };
    }
}
