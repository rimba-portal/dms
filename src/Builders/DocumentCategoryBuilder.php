<?php

declare(strict_types=1);

namespace Rimba\Dms\Builders;

use Illuminate\Database\Eloquent\Builder;

class DocumentCategoryBuilder extends Builder
{
    public function roots(): static
    {
        return $this->whereNull('parent_id');
    }
}
