<?php

declare(strict_types=1);

namespace Rimba\Document\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['parent_id', 'code', 'name', 'description', 'is_controlled', 'is_active'])]
#[Table(name: 'document_types')]
class DocumentType extends Model
{
    protected function casts(): array
    {
        return [
            'parent_id' => 'integer',
            'is_controlled' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(config('document.models.document', Document::class));
    }
}
