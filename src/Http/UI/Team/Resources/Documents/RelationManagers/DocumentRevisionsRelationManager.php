<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Team\Resources\Documents\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Http\UploadedFile;
use Rimba\Document\Actions\CreateDocumentRevision;
use Rimba\Document\Enums\DocumentRevisionStatus;
use Rimba\Document\Models\Document;
use Rimba\Document\Models\DocumentRevision;

class DocumentRevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';

    protected static ?string $title = 'Revisions';

    protected static ?string $modelLabel = 'Revision';

    protected static ?string $pluralModelLabel = 'Revisions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Revision')
                    ->description(
                        'Add controlled content by uploading a document or referencing an external URL.',
                    )
                    ->columns(2)
                    ->schema([
                        TextInput::make('revision')
                            ->label('Revision')
                            ->placeholder('1.0')
                            ->required()
                            ->maxLength(50),

                        Radio::make('content_source')
                            ->label('Content Source')
                            ->options([
                                'file' => 'Upload File',
                                'url' => 'External URL',
                            ])
                            ->default('file')
                            ->inline()
                            ->live()
                            ->dehydrated(false)
                            ->required(),

                        FileUpload::make('uploaded_file')
                            ->label('Document File')
                            ->storeFiles(false)
                            ->required(
                                fn (Get $get): bool => $get('content_source') === 'file',
                            )
                            ->visible(
                                fn (Get $get): bool => $get('content_source') === 'file',
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
                            ->columnSpanFull(),

                        TextInput::make('external_url')
                            ->label('External URL')
                            ->placeholder('https://...')
                            ->url()
                            ->required(
                                fn (Get $get): bool => $get('content_source') === 'url',
                            )
                            ->visible(
                                fn (Get $get): bool => $get('content_source') === 'url',
                            )
                            ->columnSpanFull(),

                        Textarea::make('change_summary')
                            ->label('Change Summary')
                            ->placeholder(
                                'Describe the initial issue or changes introduced by this revision.',
                            )
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('revision')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextInput::make('revision')
                    ->label('Revision')
                    ->placeholder('1.0')
                    ->required()
                    ->maxLength(50)
                    ->rule(function (): \Closure {
                        return function (
                            string $attribute,
                            mixed $value,
                            \Closure $fail,
                        ): void {
                            if (
                                $this->getOwnerRecord()
                                    ->revisions()
                                    ->where('revision', $value)
                                    ->exists()
                            ) {
                                $fail(
                                    'This revision already exists for the document.',
                                );
                            }
                        };
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (DocumentRevisionStatus|string|null $state): string => $state instanceof DocumentRevisionStatus
                            ? str($state->value)->headline()->toString()
                            : str((string) $state)->headline()->toString(),
                    ),

                TextColumn::make('original_filename')
                    ->label('Content')
                    ->placeholder(
                        fn (DocumentRevision $record): string => filled($record->external_url)
                            ? 'External URL'
                            : '-',
                    )
                    ->limit(40),

                TextColumn::make('change_summary')
                    ->label('Change Summary')
                    ->limit(60)
                    ->wrap()
                    ->placeholder('-'),

                TextColumn::make('effective_at')
                    ->label('Effective')
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Revision')
                    ->icon('heroicon-o-document-plus')
                    ->using(
                        function (
                            array $data,
                        ): DocumentRevision {
                            /** @var Document $model */
                            $model = $this->getOwnerRecord();

                            $file = $data['uploaded_file'] ?? null;

                            unset(
                                $data['uploaded_file'],
                                $data['content_source'],
                            );

                            $documentRevision = app(
                                CreateDocumentRevision::class,
                            )->handle(
                                document: $model,
                                attributes: $data,
                                file: $file instanceof UploadedFile
                                    ? $file
                                    : null,
                            );

                            Notification::make()
                                ->success()
                                ->title('Revision created')
                                ->body(
                                    "Revision {$documentRevision->revision} was created as a draft.",
                                )
                                ->send();

                            return $documentRevision;
                        },
                    ),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->label('Delete Draft')
                    ->requiresConfirmation()
                    ->visible(
                        fn (DocumentRevision $record): bool => $record->status === DocumentRevisionStatus::Draft,
                    ),
            ])
            ->emptyStateIcon('heroicon-o-document')
            ->emptyStateHeading('No revisions')
            ->emptyStateDescription(
                'Add the initial revision to begin document establishment.',
            );
    }
}
