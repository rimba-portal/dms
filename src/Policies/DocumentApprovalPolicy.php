<?php

declare(strict_types=1);

namespace Rimba\Dms\Policies;

use Illuminate\Foundation\Auth\User;

class DocumentApprovalPolicy
{
    public function approve(User $user): bool
    {
        return $user->can('dms.approval.approve');
    }
}
