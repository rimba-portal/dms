<?php

declare(strict_types=1);

namespace Rimba\Document\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Packstub\Flow\Concerns\HasWorkflows;
use Rimba\Document\Enums\DocumentRevisionStatus;

#[Fillable([
    'document_id',
    'revision',
    'status',
    'file_disk',
    'file_path',
    'original_filename',
    'mime_type',
    'file_size',
    'checksum',
    'change_summary',
    'question_bank',
    'issued_at',
    'effective_at',
    'expires_at',
    'created_by',
    'approved_by',
    'approved_at',
])]
#[Table(name: 'document_revisions')]
class DocumentRevision extends Model
{
    use HasWorkflows;

    protected function casts(): array
    {
        return [
            'document_id' => 'integer',
            'status' => DocumentRevisionStatus::class,
            'file_size' => 'integer',
            'question_bank' => 'array',
            'issued_at' => 'datetime',
            'effective_at' => 'datetime',
            'expires_at' => 'datetime',
            'created_by' => 'integer',
            'approved_by' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(config('document.models.document', Document::class));
    }

    public function questionCount(): Attribute
    {
        return Attribute::get(fn (): int => count($this->question_bank ?? []));
    }

    public function hasQuestionBank(): bool
    {
        return $this->question_count > 0;
    }
}
