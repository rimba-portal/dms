<?php

declare(strict_types=1);

namespace Rimba\Dms\Policies;

use Illuminate\Foundation\Auth\User;
use Rimba\Dms\Models\Document;

class DocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('dms.document.view');
    }

    public function view(User $user, Document $document): bool
    {
        return $user->can('dms.document.view');
    }

    public function create(User $user): bool
    {
        return $user->can('dms.document.create');
    }

    public function update(User $user, Document $document): bool
    {
        return $user->can('dms.document.update');
    }

    public function approve(User $user, Document $document): bool
    {
        return $user->can('dms.document.approve');
    }

    public function release(User $user, Document $document): bool
    {
        return $user->can('dms.document.release');
    }

    public function delete(User $user, Document $document): bool
    {
        return false;
    }
}
