<?php

declare(strict_types=1);

namespace Rimba\Dms\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Rimba\Dms\Models\Document;

class SendAcknowledgementReminder implements ShouldQueue
{
    use Queueable;

    public function __construct(public Document $document) {}

    public function handle(): void
    { /* Send acknowledgement reminder notification. */
    }
}
