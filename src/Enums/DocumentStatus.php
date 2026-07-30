<?php

declare(strict_types=1);

namespace Rimba\Dms\Enums;

enum DocumentStatus: string
{
    case Draft = 'draft';
    case Review = 'review';
    case Approved = 'approved';
    case Released = 'released';
    case Obsolete = 'obsolete';
    case Archived = 'archived';

    public function label(): string
    {
        return ucwords(str_replace('_', ' ', $this->value));
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Review => 'warning',
            self::Approved => 'info',
            self::Released => 'success',
            self::Obsolete => 'danger',
            self::Archived => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case): array => [$case->value => $case->label()])->all();
    }
}
