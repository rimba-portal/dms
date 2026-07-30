<?php

declare(strict_types=1);

namespace Rimba\Dms\Enums;

enum SecurityClassification: string
{
    case Public = 'public';
    case Internal = 'internal';
    case Restricted = 'restricted';
    case HighlyConfidential = 'highly_confidential';

    public function label(): string
    {
        return ucwords(str_replace('_', ' ', $this->value));
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case): array => [$case->value => $case->label()])->all();
    }
}
