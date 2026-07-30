<?php

declare(strict_types=1);

namespace Rimba\Dms\Listeners;

use Rimba\Dms\Events\DocumentReleased;

class GenerateTrainingAssignments
{
    public function handle(DocumentReleased $event): void
    {
        if (! $event->document->requires_training) {
            return;
        }

        // Integrate with rimba/lms here.
    }
}
