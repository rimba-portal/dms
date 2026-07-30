<?php

declare(strict_types=1);

namespace Rimba\Dms\Services;

use Rimba\Dms\Models\Document;

class DocumentComplianceService
{
    public function acknowledgementPercent(Document $document): int
    {
        $total = $document->acknowledgements()->count();
        if ($total === 0) {
            return 0;
        }

        $done = $document->acknowledgements()->whereNotNull('acknowledged_at')->count();

        return (int) round(($done / $total) * 100);
    }

    public function isReviewDue(Document $document): bool
    {
        return filled($document->next_review_date) && $document->next_review_date->isPast();
    }
}
