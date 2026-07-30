<?php

declare(strict_types=1);

use Rimba\Dms\Enums\DocumentStatus;
use Rimba\Dms\Enums\SecurityClassification;

return [
    'tables' => [
        'categories' => 'document_categories',
        'documents' => 'documents',
        'approvals' => 'document_approvals',
        'signatures' => 'document_signatures',
        'distributions' => 'document_distributions',
        'acknowledgements' => 'document_acknowledgements',
        'reviews' => 'document_reviews',
        'trainings' => 'document_trainings',
        'attachments' => 'document_attachments',
        'retentions' => 'document_retentions',
    ],

    'defaults' => [
        'status' => DocumentStatus::Draft->value,
        'security_classification' => SecurityClassification::Internal->value,
        'retention_period_years' => 5,
        'review_interval_months' => 12,
        'controlled' => true,
    ],

    'document_types' => [
        'manual' => 'Quality Manual',
        'procedure' => 'Procedure',
        'sop' => 'Standard Operating Procedure',
        'work_instruction' => 'Work Instruction',
        'form' => 'Form',
        'policy' => 'Policy',
        'external' => 'External Document',
        'record' => 'Record',
    ],
];
