<?php

declare(strict_types=1);

namespace Rimba\Dms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rimba\Dms\Policies\DocumentReviewPolicy;

#[Table('dms_document_reviews')]
#[UsePolicy(DocumentReviewPolicy::class)]
#[Fillable(['document_id', 'reviewer_id', 'status', 'due_date', 'completed_at', 'notes'])]
class DocumentReview extends Model
{
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    protected function casts(): array
    {
        return ['due_date' => 'date', 'completed_at' => 'datetime'];
    }
}
