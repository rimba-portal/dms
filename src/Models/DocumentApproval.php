<?php

declare(strict_types=1);

namespace Rimba\Dms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rimba\Dms\Policies\DocumentApprovalPolicy;
use Rimba\Versioning\Models\Version;

#[Table('dms_document_approvals')]
#[UsePolicy(DocumentApprovalPolicy::class)]
#[Fillable(['document_id', 'version_id', 'approver_id', 'status', 'approved_at', 'comments'])]
class DocumentApproval extends Model
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
        return ['approved_at' => 'datetime'];
    }
}
