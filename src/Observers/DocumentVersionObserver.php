<?php

declare(strict_types=1);

namespace Rimba\Dms\Observers;

class DocumentVersionObserver
{
    public function created($version): void
    {
        // Hook point for checksum/signature validation.
    }
}
