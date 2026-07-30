<?php

declare(strict_types=1);

namespace Rimba\Dms\Builders;

use Illuminate\Database\Eloquent\Builder;
use Rimba\Dms\Enums\DocumentStatus;

class DocumentBuilder extends Builder
{
    public function controlled(): static
    {
        return $this->where('is_controlled', true);
    }

    public function released(): static
    {
        return $this->where('status', DocumentStatus::Released->value);
    }

    public function dueForReview(): static
    {
        return $this->whereNotNull('next_review_date')->whereDate('next_review_date', '<=', now());
    }

    public function obsolete(): static
    {
        return $this->where('status', DocumentStatus::Obsolete->value);
    }
}
