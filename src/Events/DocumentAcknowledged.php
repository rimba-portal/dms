<?php

declare(strict_types=1);

namespace Rimba\Dms\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Rimba\Dms\Models\Document;

class DocumentAcknowledged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Document $document, public int $staffId) {}
}
