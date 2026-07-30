<?php

declare(strict_types=1);

namespace Rimba\Dms\Enums;

enum ReviewStatus: string
{
    case Open = 'open';
    case Completed = 'completed';
    case Overdue = 'overdue';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return ucwords($this->value);
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case): array => [$case->value => $case->label()])->all();
    }
}
