<?php

declare(strict_types=1);

namespace Rimba\Dms\Observers;

use Rimba\Dms\Models\Document;

class DocumentObserver
{
    public function creating(Document $document): void
    {
        $document->status ??= config('rimba_dms.defaults.status', 'draft');
        $document->security_classification ??= config('rimba_dms.defaults.security_classification', 'internal');
        $document->retention_period_years ??= config('rimba_dms.defaults.retention_period_years', 5);
        $document->is_controlled ??= config('rimba_dms.defaults.controlled', true);
    }

    public function updated(Document $document): void
    {
        // Hook point for rimba/jejak audit trail.
    }
}
