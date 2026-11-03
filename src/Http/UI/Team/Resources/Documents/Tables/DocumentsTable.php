<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Team\Resources\Documents\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Rimba\Base\Support\LinkViewResolver;
use Rimba\Document\Enums\DocumentStatus;
use Rimba\Document\Http\UI\Team\Resources\Documents\DocumentResource;
use Rimba\Document\Models\Document;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return LinkViewResolver::attach(
            $table,
            DocumentResource::class,
        )
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('code')
                    ->label('Number')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('title')
                    ->label('Document')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(
                        fn (Document $record): ?string => filled($record->description)
                                ? str($record->description)
                                    ->limit(80)
                                    ->toString()
                                : null,
                    ),

                TextColumn::make('type.name')
                    ->label('Type')
                    ->badge()
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (DocumentStatus|string|null $state): string => $state instanceof DocumentStatus
                                ? str($state->value)->headline()->toString()
                                : str((string) $state)->headline()->toString(),
                    )
                    ->sortable(),

                TextColumn::make('currentRevision.revision')
                    ->label('Current Revision')
                    ->badge()
                    ->placeholder('Not effective'),

                TextColumn::make('owner.name')
                    ->label('Owner')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('document_type_id')
                    ->label('Document Type')
                    ->relationship(
                        'type',
                        'name',
                    )
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(
                        collect(DocumentStatus::cases())
                            ->mapWithKeys(
                                fn (DocumentStatus $status): array => [
                                    $status->value => str($status->value)
                                        ->headline()
                                        ->toString(),
                                ],
                            )
                            ->all(),
                    ),
            ])
            ->recordActions([
                ViewAction::make(),

                EditAction::make()
                    ->label('Edit Metadata'),
            ])
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateHeading('No documents')
            ->emptyStateDescription(
                'Create the first controlled document for this team.',
            );
    }
}
