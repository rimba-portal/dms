<?php

declare(strict_types=1);

namespace Rimba\Dms\Actions;

use Rimba\Dms\Enums\DocumentStatus;
use Rimba\Dms\Models\Document;

class ObsoleteDocument
{
    public function execute(Document $document): Document
    {
        $document->update([
            'status' => DocumentStatus::Obsolete,
            'obsolete_date' => now()->toDateString(),
        ]);

        return $document->refresh();
    }
}
