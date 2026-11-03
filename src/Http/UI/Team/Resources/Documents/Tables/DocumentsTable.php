<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Team\Resources\Documents\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Rimba\Base\Support\LinkViewResolver;
use Rimba\Document\Http\UI\Team\Resources\Documents\Actions\AddVersionAction;
use Rimba\Document\Http\UI\Team\Resources\Documents\DocumentResource;

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
                        fn ($record): ?string => filled($record->description)
                            ? str($record->description)
                                ->limit(80)
                                ->toString()
                            : null
                    ),

                TextColumn::make('type.name')
                    ->label('Type')
                    ->badge()
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('currentVersion.version')
                    ->label('Current Version')
                    ->badge()
                    ->placeholder('Not released'),

                TextColumn::make('owner.name')
                    ->label('Owner')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('next_review_date')
                    ->label('Next Review')
                    ->date()
                    ->sortable()
                    ->placeholder('-'),

                IconColumn::make('controlled')
                    ->label('Controlled')
                    ->boolean()
                    ->toggleable(),

                IconColumn::make('requires_training')
                    ->label('Training')
                    ->boolean()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(
                        isToggledHiddenByDefault: true,
                    ),
            ])
            ->filters([
                SelectFilter::make('document_type_id')
                    ->label('Document Type')
                    ->relationship('type', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),

                AddVersionAction::make(),

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
