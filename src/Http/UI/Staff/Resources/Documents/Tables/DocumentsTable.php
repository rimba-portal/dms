<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Staff\Resources\Documents\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Rimba\Base\Support\LinkViewResolver;
use Rimba\Document\Http\UI\Staff\Resources\Documents\DocumentResource;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return LinkViewResolver::attach(
            $table,
            DocumentResource::class,
        )
            ->defaultSort('title')
            ->columns([
                TextColumn::make('code')
                    ->label('Number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('title')
                    ->label('Document')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('type.name')
                    ->label('Type')
                    ->badge()
                    ->placeholder('-'),

                TextColumn::make('currentRevision.revision')
                    ->label('Revision')
                    ->badge()
                    ->placeholder('-'),

                TextColumn::make('currentRevision.effective_at')
                    ->label('Effective')
                    ->date()
                    ->placeholder('-'),

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
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->emptyStateIcon('heroicon-o-book-open')
            ->emptyStateHeading('No documents available');
    }
}
