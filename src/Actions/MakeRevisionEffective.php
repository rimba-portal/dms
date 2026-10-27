<?php

declare(strict_types=1);

namespace Rimba\Document\Actions;

use Illuminate\Support\Facades\DB;
use LogicException;
use Rimba\Document\Enums\DocumentRevisionStatus;
use Rimba\Document\Enums\DocumentStatus;
use Rimba\Document\Events\DocumentRevisionMadeEffective;
use Rimba\Document\Models\DocumentRevision;

final class MakeRevisionEffective
{
    public function handle(DocumentRevision $revision): DocumentRevision
    {
        if (! in_array($revision->status, [
            DocumentRevisionStatus::Approved,
            DocumentRevisionStatus::Effective,
        ], true)) {
            throw new LogicException('Only an approved revision can be made effective.');
        }

        return DB::transaction(function () use ($revision): DocumentRevision {
            $revision->document->revisions()
                ->whereKeyNot($revision->getKey())
                ->where('status', DocumentRevisionStatus::Effective->value)
                ->update(['status' => DocumentRevisionStatus::Superseded->value]);

            $revision->update([
                'status' => DocumentRevisionStatus::Effective,
                'effective_at' => $revision->effective_at ?? now(),
            ]);

            $revision->document->update([
                'current_revision_id' => $revision->getKey(),
                'status' => DocumentStatus::Active,
            ]);

            DocumentRevisionMadeEffective::dispatch($revision);

            return $revision->refresh();
        });
    }
}
