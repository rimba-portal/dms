<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Team\Resources\Documents\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Rimba\Versioning\Enums\ContentType;
use Rimba\Versioning\Enums\VersionIncrementType;

class DocumentVersionForm
{
    /**
     * @return array<int, mixed>
     */
    public static function components(
        string $prefix = 'version',
        bool $initial = false,
    ): array {
        $field = static fn (string $name): string => "{$prefix}.{$name}";

        $contentTypeField = $field('content_type');

        return [
            Select::make($field('revision_type'))
                ->label('Version Increment')
                ->options([
                    VersionIncrementType::Major->value => 'Major',
                    VersionIncrementType::Minor->value => 'Minor',
                    VersionIncrementType::Patch->value => 'Patch',
                ])
                ->default(
                    $initial
                        ? VersionIncrementType::Major->value
                        : VersionIncrementType::Minor->value
                )
                ->disabled($initial)
                ->dehydrated()
                ->required()
                ->native(false)
                ->helperText(
                    $initial
                        ? 'The initial version will be created as Version 1.0.0.'
                        : 'Select how the next semantic version should be incremented.'
                )
                ->columnSpan([
                    'default' => 12,
                    'lg' => 4,
                ]),

            Select::make($contentTypeField)
                ->label('Content Source')
                ->options([
                    ContentType::File->value => 'Upload File',
                    ContentType::Url->value => 'External URL',
                ])
                ->default(ContentType::File->value)
                ->required()
                ->live()
                ->native(false)
                ->columnSpan([
                    'default' => 12,
                    'lg' => 4,
                ]),

            FileUpload::make($field('uploaded_file'))
                ->label('Document File')
                ->disk('documents')
                ->directory('dms')
                ->visibility('private')
                ->preserveFilenames()
                ->storeFileNamesIn($field('original_name'))
                ->required(
                    fn (Get $get): bool => $get($contentTypeField)
                        === ContentType::File->value
                )
                ->visible(
                    fn (Get $get): bool => $get($contentTypeField)
                        === ContentType::File->value
                )
                ->acceptedFileTypes([
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'text/plain',
                    'text/csv',
                    'image/jpeg',
                    'image/png',
                ])
                ->maxSize(51200)
                ->downloadable()
                ->openable()
                ->previewable()
                ->columnSpanFull(),

            TextInput::make($field('external_url'))
                ->label('External Content URL')
                ->placeholder('https://...')
                ->url()
                ->required(
                    fn (Get $get): bool => $get($contentTypeField)
                        === ContentType::Url->value
                )
                ->visible(
                    fn (Get $get): bool => $get($contentTypeField)
                        === ContentType::Url->value
                )
                ->maxLength(65535)
                ->columnSpanFull(),

            TextInput::make($field('original_name'))
                ->hidden()
                ->dehydrated(),

            Textarea::make($field('notes'))
                ->label('Version Notes')
                ->placeholder(
                    $initial
                        ? 'Describe the initial version.'
                        : 'Describe the changes introduced by this version.'
                )
                ->rows(4)
                ->maxLength(65535)
                ->columnSpanFull(),
        ];
    }

    public static function mutateVersionData(
        array $data,
    ): array {
        $contentType = $data['content_type']
            ?? ContentType::File->value;

        if ($contentType === ContentType::File->value) {
            $data['content_url'] =
                $data['uploaded_file'] ?? null;
        }

        if ($contentType === ContentType::Url->value) {
            $data['content_url'] =
                $data['external_url'] ?? null;

            $data['original_name'] = null;
        }

        unset(
            $data['uploaded_file'],
            $data['external_url'],
        );

        return $data;
    }
}
