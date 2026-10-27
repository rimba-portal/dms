<?php

declare(strict_types=1);

namespace Rimba\Document\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Packstub\Flow\Concerns\HasWorkflows;
use Rimba\Attributing\Traits\HasThingAttributes;
use Rimba\Document\Enums\DocumentStatus;

#[Fillable([
    'document_type_id',
    'current_revision_id',
    'code',
    'title',
    'description',
    'status',
    'owner_type',
    'owner_id',
])]
#[Table(name: 'documents')]
class Document extends Model
{
    use HasThingAttributes;
    use HasWorkflows;

    protected function casts(): array
    {
        return [
            'document_type_id' => 'integer',
            'current_revision_id' => 'integer',
            'owner_id' => 'integer',
            'status' => DocumentStatus::class,
        ];
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(
            config('document.models.document_type', DocumentType::class),
            'document_type_id',
        );
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(
            config('document.models.document_revision', DocumentRevision::class)
        );
    }

    public function currentRevision(): BelongsTo
    {
        return $this->belongsTo(
            config('document.models.document_revision', DocumentRevision::class),
            'current_revision_id',
        );
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
