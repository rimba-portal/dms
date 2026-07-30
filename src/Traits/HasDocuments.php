<?php

declare(strict_types=1);

namespace Rimba\Dms\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Rimba\Dms\Models\Document;

trait HasDocuments
{
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
