<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Team\Resources\Documents\Tables;

use Filament\Actions;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Rimba\Base\Support\LinkViewResolver;
use Rimba\Document\Actions\ApproveDocument;
use Rimba\Document\Actions\ObsoleteDocument;
use Rimba\Document\Actions\SubmitDocumentForReview;
use Rimba\Document\Enums\DocumentStatus;
use Rimba\Document\Http\UI\Team\Resources\Documents\Actions\AddVersionAction;
use Rimba\Document\Http\UI\Team\Resources\Documents\DocumentResource;
use Rimba\Document\Models\Document;

class DocumentsTable
{
    // use HasRecordLinks;

    public static function configure(Table $table): Table
    {
        return LinkViewResolver::attach(
            $table,
            DocumentResource::class
        )
            ->columns([
                TextColumn::make('file_name'),
                TextColumn::make('type'),
                TextColumn::make('currentVersion.version')
                    ->label('Current Version')
                    ->badge()
                    ->placeholder('Not released')
                    ->sortable(),

                TextColumn::make('currentVersion.content_type')
                    ->label('Content')
                    ->badge()
                    ->placeholder('-')
                    ->toggleable(),

            ])
            ->recordActions([
                Actions\ViewAction::make(),

                AddVersionAction::make(),

                Actions\EditAction::make()
                    ->label('Edit Metadata'),

                Actions\Action::make('submitForReview')
                    ->label('Submit Review')
                    ->icon(Heroicon::OutlinedArrowUpTray)
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(
                        fn (Document $record): bool => self::statusIs(
                            $record,
                            DocumentStatus::Draft,
                        )
                    )
                    ->action(
                        fn (Document $record): Document => app(SubmitDocumentForReview::class)
                            ->execute($record)
                    ),

                Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(
                        fn (Document $record): bool => self::statusIs(
                            $record,
                            DocumentStatus::Review,
                        )
                    )
                    ->action(
                        fn (Document $record): Document => app(ApproveDocument::class)
                            ->execute($record)
                    ),

                Actions\Action::make('obsolete')
                    ->label('Obsolete')
                    ->icon(
                        Heroicon::OutlinedArchiveBoxXMark
                    )
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(
                        fn (Document $record): bool => ! self::statusIs(
                            $record,
                            DocumentStatus::Obsolete,
                        )
                    )
                    ->action(
                        fn (Document $record): Document => app(ObsoleteDocument::class)
                            ->execute($record)
                    ),
            ]);
    }

    protected static function statusIs(
        Document $record,
        DocumentStatus $status,
    ): bool {
        return $record->status instanceof DocumentStatus
            ? $record->status === $status
            : $record->status === $status->value;
    }
}
