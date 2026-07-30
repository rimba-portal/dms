<?php

declare(strict_types=1);

namespace Rimba\Dms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rimba\Versioning\Models\Version;

#[Table('dms_document_acknowledgements')]
#[Fillable(['document_id', 'version_id', 'staff_id', 'read_at', 'acknowledged_at'])]
class DocumentAcknowledgement extends Model
{
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    protected function casts(): array
    {
        return ['read_at' => 'datetime', 'acknowledged_at' => 'datetime'];
    }
}
