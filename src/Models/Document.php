<?php

declare(strict_types=1);

namespace Rimba\Dms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rimba\Dms\Builders\DocumentBuilder;
use Rimba\Dms\Enums\DocumentStatus;
use Rimba\Dms\Enums\SecurityClassification;
use Rimba\Dms\Observers\DocumentObserver;
use Rimba\Dms\Policies\DocumentPolicy;
use Rimba\Organization\Models\OrgTeam;
use Rimba\People\Models\Staff;
use Rimba\Versioning\Models\Version;
use Rimba\Versioning\Traits\HasVersions;

#[Table('dms_documents')]
#[UsePolicy(DocumentPolicy::class)]
#[ObservedBy([DocumentObserver::class])]
#[Fillable([
    'parent_id',
    'category_id',
    'doc_number',
    'title',
    'document_type',
    'status',
    'is_controlled',
    'team_id',
    'owner_id',
    'author_id',
    'current_version_id',
    'site_location',
    'security_classification',
    'regulatory_impact',
    'risk_assessment_tags',
    'requires_training',
    'retention_period_years',
    'effective_date',
    'next_review_date',
    'approved_date',
    'obsolete_date',
    'regulatory_hash',
])]
class Document extends Model
{
    use HasVersions;
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => DocumentStatus::class,
            'security_classification' => SecurityClassification::class,
            'is_controlled' => 'boolean',
            'requires_training' => 'boolean',
            'risk_assessment_tags' => 'array',
            'effective_date' => 'date',
            'next_review_date' => 'date',
            'approved_date' => 'date',
            'obsolete_date' => 'date',
        ];
    }

    public function newEloquentBuilder($query): DocumentBuilder
    {
        return new DocumentBuilder($query);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    public function currentVersion(): BelongsTo
    {
        return $this->belongsTo(Version::class, 'current_version_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(DocumentApproval::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(DocumentSignature::class);
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(DocumentDistribution::class);
    }

    public function acknowledgements(): HasMany
    {
        return $this->hasMany(DocumentAcknowledgement::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(DocumentReview::class);
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(DocumentTraining::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(DocumentAttachment::class);
    }

    public function retentions(): HasMany
    {
        return $this->hasMany(DocumentRetention::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class, 'team_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'owner_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'author_id');
    }
}
