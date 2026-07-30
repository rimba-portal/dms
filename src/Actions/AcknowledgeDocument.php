<?php

declare(strict_types=1);

namespace Rimba\Dms\Actions;

use Rimba\Dms\Models\Document;

class AcknowledgeDocument
{
    public function execute(Document $document, int $staffId): void
    {
        $document->acknowledgements()->updateOrCreate(
            ['version_id' => $document->current_version_id, 'staff_id' => $staffId],
            ['read_at' => now(), 'acknowledged_at' => now()],
        );
    }
}
