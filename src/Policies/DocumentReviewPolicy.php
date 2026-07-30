<?php

declare(strict_types=1);

namespace Rimba\Dms\Policies;

use Illuminate\Foundation\Auth\User;

class DocumentReviewPolicy
{
    public function complete(User $user): bool
    {
        return $user->can('dms.review.complete');
    }
}
