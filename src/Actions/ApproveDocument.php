<?php

declare(strict_types=1);

namespace Rimba\Dms\Actions;

use Rimba\Dms\Enums\ApprovalStatus;
use Rimba\Dms\Enums\DocumentStatus;
use Rimba\Dms\Events\DocumentApproved;
use Rimba\Dms\Models\Document;
use Rimba\Versioning\Models\Version;

class ApproveDocument
{
    public function execute(Document $document, ?Version $version = null, ?int $approverId = null, ?string $comments = null): Document
    {
        $model = $document->approvals()->create([
            'version_id' => $version?->getKey(),
            'approver_id' => $approverId,
            'status' => ApprovalStatus::Approved->value,
            'approved_at' => now(),
            'comments' => $comments,
        ]);

        $document->update([
            'status' => DocumentStatus::Approved,
            'approved_date' => now()->toDateString(),
        ]);

        event(new DocumentApproved($document, $model));

        return $document->refresh();
    }
}
