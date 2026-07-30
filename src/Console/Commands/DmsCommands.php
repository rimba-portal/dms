<?php

declare(strict_types=1);

namespace Rimba\Dms\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Rimba\Dms\Jobs\SendDocumentReviewReminder;
use Rimba\Dms\Models\Document;

#[Description('List controlled documents due for review.')]
#[Signature('dms:review-due {--dispatch : Dispatch reminder jobs}')]
class DmsCommands extends Command
{
    public function handle(): int
    {
        $documents = Document::query()->controlled()->dueForReview()->get();

        $this->table(['ID', 'No', 'Title', 'Review Date'], $documents->map(fn (Document $document): array => [
            $document->id,
            $document->doc_number,
            $document->title,
            optional($document->next_review_date)->toDateString(),
        ])->all());

        if ($this->option('dispatch')) {
            $documents->each(fn (Document $document) => SendDocumentReviewReminder::dispatch($document));
        }

        return self::SUCCESS;
    }
}
