<?php

declare(strict_types=1);

use Rimba\Document\Models\Document;
use Rimba\Document\Models\DocumentRevision;
use Rimba\Document\Models\DocumentType;

return [
    'models' => [
        'document' => Document::class,
        'document_type' => DocumentType::class,
        'document_revision' => DocumentRevision::class,
    ],

    'disk' => env('DOCUMENT_DISK', 'local'),
    'directory' => env('DOCUMENT_DIRECTORY', 'documents'),

    'workflows' => [
        'document' => true,
        'document_revision' => true,
    ],

    'question_bank' => [
        'minimum_answers' => 2,
        'maximum_answers' => 10,
    ],
];
