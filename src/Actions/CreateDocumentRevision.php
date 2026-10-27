<?php

declare(strict_types=1);

namespace Rimba\Document\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Rimba\Document\Enums\DocumentRevisionStatus;
use Rimba\Document\Events\DocumentRevisionCreated;
use Rimba\Document\Models\Document;
use Rimba\Document\Models\DocumentRevision;
use Rimba\Document\Support\QuestionBankValidator;

final readonly class CreateDocumentRevision
{
    public function __construct(private QuestionBankValidator $questionBankValidator) {}

    /** @param array<string, mixed> $attributes */
    public function handle(Document $document, array $attributes, ?UploadedFile $file = null): DocumentRevision
    {
        $questionBank = $this->questionBankValidator->validate($attributes['question_bank'] ?? []);

        return DB::transaction(function () use ($document, $attributes, $file, $questionBank): DocumentRevision {
            $fileData = [];

            if ($file instanceof UploadedFile) {
                $disk = (string) ($attributes['file_disk'] ?? config('document.disk', 'local'));
                $directory = trim((string) config('document.directory', 'documents'), '/');
                $path = $file->store("{$directory}/{$document->getKey()}", $disk);

                $fileData = [
                    'file_disk' => $disk,
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'checksum' => hash_file('sha256', Storage::disk($disk)->path($path)),
                ];
            }

            /** @var DocumentRevision $model */
            $model = $document->revisions()->create([
                ...Arr::only($attributes, [
                    'revision', 'change_summary', 'issued_at', 'effective_at',
                    'expires_at', 'created_by',
                ]),
                ...$fileData,
                'status' => DocumentRevisionStatus::Draft,
                'question_bank' => $questionBank,
            ]);

            DocumentRevisionCreated::dispatch($model);

            return $model;
        });
    }
}
