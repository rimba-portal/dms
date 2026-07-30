<?php

declare(strict_types=1);

namespace Rimba\Dms\Listeners;

use Rimba\Dms\Events\DocumentReleased;

class ArchiveSupersededVersions
{
    public function handle(DocumentReleased $event): void
    {
        $event->document->versions()
            ->whereKeyNot($event->version->getKey())
            ->where('status', 'released')
            ->update(['status' => 'obsolete', 'effective_until' => now()]);
    }
}
