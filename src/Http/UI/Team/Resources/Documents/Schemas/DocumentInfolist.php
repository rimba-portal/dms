<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Team\Resources\Documents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Rimba\Document\Enums\DocumentRevisionStatus;
use Rimba\Document\Enums\DocumentStatus;

final class DocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Document')
                    ->tabs([
                        Tab::make('Overview')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('Document')
                                    ->columns(2)
                                    ->schema([
                                        TextEntry::make('code')
                                            ->label('Document Number')
                                            ->weight('bold')
                                            ->copyable(),

                                        TextEntry::make('title')
                                            ->label('Title')
                                            ->weight('bold'),

                                        TextEntry::make('type.name')
                                            ->label('Document Type')
                                            ->badge()
                                            ->placeholder('-'),

                                        TextEntry::make('status')
                                            ->label('Status')
                                            ->badge()
                                            ->formatStateUsing(
                                                fn (DocumentStatus|string|null $state): string => $state instanceof DocumentStatus
                                                        ? str($state->value)->headline()->toString()
                                                        : str((string) $state)->headline()->toString(),
                                            ),

                                        TextEntry::make('owner_type')
                                            ->label('Owner Type')
                                            ->formatStateUsing(
                                                fn (?string $state): string => $state
                                                        ? class_basename($state)
                                                        : '-',
                                            ),

                                        TextEntry::make('owner_id')
                                            ->label('Owner ID')
                                            ->placeholder('-'),

                                        TextEntry::make('description')
                                            ->label('Description')
                                            ->placeholder('-')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Current Revision')
                            ->icon('heroicon-o-document-check')
                            ->schema([
                                Section::make('Effective Revision')
                                    ->description(
                                        'The currently effective controlled revision.',
                                    )
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('currentRevision.revision')
                                            ->label('Revision')
                                            ->badge()
                                            ->placeholder('Not effective'),

                                        TextEntry::make('currentRevision.status')
                                            ->label('Status')
                                            ->badge()
                                            ->placeholder('-')
                                            ->formatStateUsing(
                                                fn (DocumentRevisionStatus|string|null $state): string => $state instanceof DocumentRevisionStatus
                                                        ? str($state->value)->headline()->toString()
                                                        : str((string) $state)->headline()->toString(),
                                            ),

                                        TextEntry::make('currentRevision.original_filename')
                                            ->label('File')
                                            ->placeholder('-'),

                                        TextEntry::make('currentRevision.mime_type')
                                            ->label('Content Type')
                                            ->placeholder('-'),

                                        TextEntry::make('currentRevision.file_size')
                                            ->label('File Size')
                                            ->formatStateUsing(
                                                fn (int|string|null $state): string => self::formatBytes($state),
                                            )
                                            ->placeholder('-'),

                                        TextEntry::make('currentRevision.checksum')
                                            ->label('SHA-256')
                                            ->copyable()
                                            ->limit(24)
                                            ->placeholder('-'),

                                        TextEntry::make('currentRevision.external_url')
                                            ->label('External URL')
                                            ->url(
                                                fn ($record): ?string => $record->currentRevision?->external_url,
                                            )
                                            ->openUrlInNewTab()
                                            ->placeholder('-')
                                            ->columnSpanFull(),

                                        TextEntry::make('currentRevision.change_summary')
                                            ->label('Change Summary')
                                            ->placeholder('-')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Lifecycle')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Section::make('Revision Lifecycle')
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('currentRevision.issued_at')
                                            ->label('Issued')
                                            ->dateTime()
                                            ->placeholder('-'),

                                        TextEntry::make('currentRevision.effective_at')
                                            ->label('Effective')
                                            ->dateTime()
                                            ->placeholder('-'),

                                        TextEntry::make('currentRevision.expires_at')
                                            ->label('Expires')
                                            ->dateTime()
                                            ->placeholder('-'),

                                        TextEntry::make('currentRevision.approved_at')
                                            ->label('Approved')
                                            ->dateTime()
                                            ->placeholder('-'),

                                        TextEntry::make('currentRevision.created_by')
                                            ->label('Created By')
                                            ->placeholder('-'),

                                        TextEntry::make('currentRevision.approved_by')
                                            ->label('Approved By')
                                            ->placeholder('-'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private static function formatBytes(
        int|string|null $bytes,
    ): string {
        if ($bytes === null || $bytes === '') {
            return '-';
        }

        $bytes = (int) $bytes;

        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];

        $power = min(
            (int) floor(log($bytes, 1024)),
            count($units) - 1,
        );

        $value = $bytes / (1024 ** $power);

        return number_format(
            $value,
            $power === 0 ? 0 : 2,
        ).' '.$units[$power];
    }
}
