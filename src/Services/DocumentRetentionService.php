<?php

declare(strict_types=1);

namespace Rimba\Dms\Services;

use Rimba\Dms\Models\Document;

class DocumentRetentionService
{
    public function calculateRetainUntil(Document $document): ?string
    {
        if (! $document->effective_date) {
            return null;
        }

        return $document->effective_date
            ->copy()
            ->addYears((int) $document->retention_period_years)
            ->toDateString();
    }
}
