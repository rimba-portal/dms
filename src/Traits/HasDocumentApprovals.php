<?php

declare(strict_types=1);

namespace Rimba\Dms\Traits;

trait HasDocumentApprovals
{
    public function pendingApprovals()
    {
        return $this->approvals()->where('status', 'pending');
    }
}
