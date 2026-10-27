<?php

declare(strict_types=1);

namespace Rimba\Document\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Rimba\Document\Models\DocumentRevision;

final class DocumentRevisionCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly DocumentRevision $revision) {}
}
