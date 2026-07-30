<?php

declare(strict_types=1);

namespace Rimba\Dms\Actions;

use Illuminate\Support\Facades\DB;
use Rimba\Dms\Enums\DocumentStatus;
use Rimba\Dms\Events\DocumentReleased;
use Rimba\Dms\Models\Document;
use Rimba\Versioning\Models\Version;

class ReleaseDocument
{
    public function execute(Document $document, Version $version): Document
    {
        return DB::transaction(function () use ($document, $version): Document {
            $document->versions()
                ->whereKeyNot($version->getKey())
                ->where('status', 'released')
                ->update(['status' => 'obsolete', 'effective_until' => now()]);

            $version->update([
                'status' => 'released',
                'released_at' => now(),
                'effective_from' => $version->effective_from ?? now(),
            ]);

            $document->update([
                'status' => DocumentStatus::Released,
                'current_version_id' => $version->getKey(),
                'effective_date' => now()->toDateString(),
                'next_review_date' => now()->addMonths((int) config('rimba_dms.defaults.review_interval_months', 12))->toDateString(),
                'regulatory_hash' => hash('sha256', $document->doc_number.'|'.$version->version.'|'.now()->toISOString()),
            ]);

            event(new DocumentReleased($document, $version));

            return $document->refresh();
        });
    }
}
