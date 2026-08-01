<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\Documents\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Rimba\Dms\Actions\ApproveDocument;
use Rimba\Dms\Actions\ObsoleteDocument;
use Rimba\Dms\Actions\SubmitDocumentForReview;
use Rimba\Dms\Enums\DocumentStatus;
use Rimba\Dms\Enums\SecurityClassification;
use Rimba\Dms\Models\Document;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('doc_number')
                    ->label('No.')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->description(fn (Document $record): ?string => $record->category?->code),

                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(48)
                    ->wrap()
                    ->description(fn (Document $record): ?string => $record->site_location),

                TextColumn::make('document_type')
                    ->label('Type')
                    ->formatStateUsing(fn (?string $state): string => config('rimba_dms.document_types.'.$state, str($state)->headline()->toString()))
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (DocumentStatus|string|null $state): string => $state instanceof DocumentStatus
                        ? $state->label()
                        : str((string) $state)->headline()->toString())
                    ->color(fn (DocumentStatus|string|null $state): string => $state instanceof DocumentStatus
                        ? $state->color()
                        : match ($state) {
                            'draft' => 'gray',
                            'review' => 'warning',
                            'approved' => 'info',
                            'released' => 'success',
                            'obsolete' => 'danger',
                            'archived' => 'gray',
                            default => 'gray',
                        })
                    ->sortable(),

                TextColumn::make('security_classification')
                    ->label('Classification')
                    ->badge()
                    ->formatStateUsing(fn (SecurityClassification|string|null $state): string => $state instanceof SecurityClassification
                        ? $state->label()
                        : str((string) $state)->replace('_', ' ')->headline()->toString())
                    ->color(fn (SecurityClassification|string|null $state): string => match ($state instanceof SecurityClassification ? $state->value : $state) {
                        'public' => 'success',
                        'internal' => 'info',
                        'restricted' => 'warning',
                        'highly_confidential' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),

                IconColumn::make('is_controlled')
                    ->label('Controlled')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('requires_training')
                    ->label('Training')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('next_review_date')
                    ->label('Next Review')
                    ->date()
                    ->sortable()
                    ->color(fn (Document $record): string => $record->next_review_date?->isPast() ? 'danger' : 'gray')
                    ->description(fn (Document $record): ?string => $record->next_review_date?->isPast() ? 'Review due' : null),

                TextColumn::make('effective_date')
                    ->label('Effective')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(DocumentStatus::options()),

                SelectFilter::make('dms_document_type')
                    ->label('Document Type')
                    ->options(fn (): array => config('rimba_dms.document_types', [])),

                SelectFilter::make('security_classification')
                    ->label('Classification')
                    ->options(SecurityClassification::options()),

                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('controlled')
                    ->label('Controlled only')
                    ->query(fn (Builder $query): Builder => $query->where('is_controlled', true)),

                Filter::make('requires_training')
                    ->label('Requires training')
                    ->query(fn (Builder $query): Builder => $query->where('requires_training', true)),

                Filter::make('review_due')
                    ->label('Review due')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereNotNull('next_review_date')
                        ->whereDate('next_review_date', '<=', now())),
            ])
            ->recordActions([
                ViewAction::make(),

                EditAction::make(),

                Action::make('submitForReview')
                    ->label('Submit Review')
                    ->icon(Heroicon::OutlinedArrowUpTray)
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (Document $record): bool => self::statusIs($record, DocumentStatus::Draft))
                    ->action(fn (Document $record): Document => app(SubmitDocumentForReview::class)->execute($record)),

                Action::make('approve')
                    ->label('Approve')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Document $record): bool => self::statusIs($record, DocumentStatus::Review))
                    ->action(fn (Document $record): Document => app(ApproveDocument::class)->execute($record)),

                Action::make('obsolete')
                    ->label('Obsolete')
                    ->icon(Heroicon::OutlinedArchiveBoxXMark)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Document $record): bool => ! self::statusIs($record, DocumentStatus::Obsolete))
                    ->action(fn (Document $record): Document => app(ObsoleteDocument::class)->execute($record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => false),
                ]),
            ])
            ->emptyStateIcon(Heroicon::OutlinedDocumentText)
            ->emptyStateHeading('No documents yet')
            ->emptyStateDescription('Create your first controlled document to start the DMS lifecycle.');
    }

    protected static function statusIs(Document $record, DocumentStatus $status): bool
    {
        return $record->status instanceof DocumentStatus
            ? $record->status === $status
            : $record->status === $status->value;
    }
}
