<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Team\Resources\Documents\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Rimba\Versioning\Enums\ContentType;
use Rimba\Versioning\Enums\VersionStatus;
use Rimba\Versioning\Models\Version;

class DocumentVersionsRelationManager extends RelationManager
{
    protected static string $relationship =
        'versions';

    protected static ?string $title =
        'Version History';

    protected static ?string $recordTitleAttribute =
        'version';

    public function infolist(
        Schema $schema,
    ): Schema {
        return $schema
            ->components([
                Section::make('Version')
                    ->columns(12)
                    ->schema([
                        TextEntry::make('version')
                            ->badge()
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 2,
                            ]),

                        TextEntry::make('revision')
                            ->numeric()
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 2,
                            ]),

                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(
                                fn (
                                    VersionStatus|string|null $state,
                                ): string => $state
                                    instanceof VersionStatus
                                        ? $state->label()
                                        : str(
                                            (string) $state
                                        )
                                            ->headline()
                                            ->toString()
                            )
                            ->color(
                                fn (
                                    VersionStatus|string|null $state,
                                ): string => $state
                                    instanceof VersionStatus
                                        ? $state->color()
                                        : match ($state) {
                                            'draft' => 'gray',
                                            'review' => 'warning',
                                            'released' => 'success',
                                            'deprecated' => 'danger',
                                            'archived' => 'gray',
                                            default => 'gray',
                                        }
                            )
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 2,
                            ]),

                        TextEntry::make('content_type')
                            ->label('Content Type')
                            ->badge()
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 2,
                            ]),

                        TextEntry::make('original_name')
                            ->label('Original Filename')
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 4,
                            ]),

                        TextEntry::make('content_url')
                            ->label('Content Locator')
                            ->copyable()
                            ->columnSpanFull(),

                        TextEntry::make('mime_type')
                            ->label('MIME Type')
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 4,
                            ]),

                        TextEntry::make('content_size')
                            ->label('File Size')
                            ->formatStateUsing(
                                fn (
                                    int|string|null $state,
                                ): string => filled($state)
                                    ? Number::fileSize(
                                        (int) $state
                                    )
                                    : '-'
                            )
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 4,
                            ]),

                        TextEntry::make('checksum')
                            ->label('SHA-256 Checksum')
                            ->placeholder('-')
                            ->copyable()
                            ->columnSpanFull(),

                        TextEntry::make('effective_from')
                            ->dateTime()
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextEntry::make('effective_until')
                            ->dateTime()
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextEntry::make('released_at')
                            ->dateTime()
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextEntry::make('upload_by')
                            ->label('Uploaded By')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextEntry::make('notes')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(
        Table $table,
    ): Table {
        return $table
            ->defaultSort(
                'revision',
                'desc',
            )
            ->columns([
                TextColumn::make('version')
                    ->label('Version')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('revision')
                    ->label('Revision')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(
                        fn (
                            VersionStatus|string|null $state,
                        ): string => $state
                            instanceof VersionStatus
                                ? $state->label()
                                : str((string) $state)
                                    ->headline()
                                    ->toString()
                    )
                    ->color(
                        fn (
                            VersionStatus|string|null $state,
                        ): string => $state
                            instanceof VersionStatus
                                ? $state->color()
                                : match ($state) {
                                    'draft' => 'gray',
                                    'review' => 'warning',
                                    'released' => 'success',
                                    'deprecated' => 'danger',
                                    'archived' => 'gray',
                                    default => 'gray',
                                }
                    )
                    ->sortable(),

                TextColumn::make('content_type')
                    ->label('Source')
                    ->badge()
                    ->formatStateUsing(
                        fn (
                            ContentType|string|null $state,
                        ): string => $state
                            instanceof ContentType
                                ? $state->label()
                                : str((string) $state)
                                    ->headline()
                                    ->toString()
                    )
                    ->sortable(),

                TextColumn::make('original_name')
                    ->label('Content')
                    ->placeholder('External Content')
                    ->limit(40)
                    ->description(
                        fn (
                            Version $record,
                        ): string => match (
                            $record->content_type
                                instanceof ContentType
                                    ? $record
                                        ->content_type
                                        ->value
                                    : $record->content_type
                        ) {
                            ContentType::File->value => $record->mime_type
                                    ?? 'Uploaded file',

                            ContentType::Url->value => str(
                                $record->content_url
                            )
                                ->limit(55)
                                ->toString(),

                            default => str(
                                $record->content_url
                            )
                                ->limit(55)
                                ->toString(),
                        }
                    )
                    ->searchable(),

                TextColumn::make('content_size')
                    ->label('Size')
                    ->formatStateUsing(
                        fn (
                            int|string|null $state,
                        ): string => filled($state)
                            ? Number::fileSize(
                                (int) $state
                            )
                            : '-'
                    )
                    ->toggleable(),

                TextColumn::make('upload_by')
                    ->label('Uploaded By')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('released_at')
                    ->label('Released')
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(
                        VersionStatus::options()
                    ),

                SelectFilter::make('content_type')
                    ->label('Content Source')
                    ->options([
                        ContentType::File->value => 'Uploaded File',

                        ContentType::Url->value => 'External URL',
                    ]),
            ])
            ->headerActions([
                /*
                 * Intentionally empty.
                 *
                 * Version creation must use AddVersionAction
                 * on ViewDocument.
                 */
            ])
            ->recordActions([
                ViewAction::make(),

                Action::make('openContent')
                    ->label('Open')
                    ->icon(
                        Heroicon::OutlinedArrowTopRightOnSquare
                    )
                    ->url(
                        fn (
                            Version $record,
                        ): ?string => $record->url()
                    )
                    ->openUrlInNewTab(
                        fn (
                            Version $record,
                        ): bool => $record->openInNewTab()
                    )
                    ->visible(
                        fn (
                            Version $record,
                        ): bool => filled($record->content_url)
                    ),
            ])
            ->toolbarActions([
                /*
                 * No bulk deletion.
                 * Version history is controlled data.
                 */
            ])
            ->emptyStateIcon(
                Heroicon::OutlinedDocumentDuplicate
            )
            ->emptyStateHeading(
                'No version history'
            )
            ->emptyStateDescription(
                'A valid document should always have an initial version.'
            );
    }
}
