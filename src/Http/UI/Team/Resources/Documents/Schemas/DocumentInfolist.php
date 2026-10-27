<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Team\Resources\Documents\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;
use Rimba\Dms\Enums\DocumentStatus;
use Rimba\Dms\Enums\SecurityClassification;
use Rimba\Versioning\Enums\VersionStatus;

class DocumentInfolist
{
    public static function configure(
        Schema $schema,
    ): Schema {
        return $schema
            ->components([
                Section::make('Document')
                    ->columns(12)
                    ->schema([
                        TextEntry::make('doc_number')
                            ->label('Document Number')
                            ->copyable()
                            ->weight('bold')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextEntry::make('title')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 6,
                            ]),

                        TextEntry::make('document_type')
                            ->label('Document Type')
                            ->badge()
                            ->formatStateUsing(
                                fn (
                                    ?string $state,
                                ): string => config(
                                    "rimba_dms.document_types.{$state}",
                                    str((string) $state)
                                        ->headline()
                                        ->toString(),
                                )
                            )
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextEntry::make('category.name')
                            ->label('Category')
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextEntry::make('team.name')
                            ->label('Owning Team')
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextEntry::make('owner.name')
                            ->label('Owner')
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextEntry::make('author.name')
                            ->label('Author')
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),
                    ]),

                Section::make('Document Control')
                    ->columns(12)
                    ->schema([
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(
                                fn (
                                    DocumentStatus|string|null $state,
                                ): string => $state
                                    instanceof DocumentStatus
                                        ? $state->label()
                                        : str(
                                            (string) $state
                                        )
                                            ->headline()
                                            ->toString()
                            )
                            ->color(
                                fn (
                                    DocumentStatus|string|null $state,
                                ): string => $state
                                    instanceof DocumentStatus
                                        ? $state->color()
                                        : 'gray'
                            )
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextEntry::make(
                            'security_classification'
                        )
                            ->label('Classification')
                            ->badge()
                            ->formatStateUsing(
                                fn (
                                    SecurityClassification|string|null $state,
                                ): string => $state
                                    instanceof SecurityClassification
                                        ? $state->label()
                                        : str(
                                            (string) $state
                                        )
                                            ->replace('_', ' ')
                                            ->headline()
                                            ->toString()
                            )
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        IconEntry::make('is_controlled')
                            ->label('Controlled')
                            ->boolean()
                            ->columnSpan([
                                'default' => 6,
                                'lg' => 3,
                            ]),

                        IconEntry::make(
                            'requires_training'
                        )
                            ->label('Requires Training')
                            ->boolean()
                            ->columnSpan([
                                'default' => 6,
                                'lg' => 3,
                            ]),
                    ]),

                Section::make(
                    'Current Released Version'
                )
                    ->columns(12)
                    ->schema([
                        TextEntry::make(
                            'currentVersion.version'
                        )
                            ->label('Version')
                            ->badge()
                            ->placeholder(
                                'No released version'
                            )
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 2,
                            ]),

                        TextEntry::make(
                            'currentVersion.status'
                        )
                            ->label('Status')
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
                                        : 'gray'
                            )
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 2,
                            ]),

                        TextEntry::make(
                            'currentVersion.content_type'
                        )
                            ->label('Content Type')
                            ->badge()
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 2,
                            ]),

                        TextEntry::make(
                            'currentVersion.original_name'
                        )
                            ->label('Filename')
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextEntry::make(
                            'currentVersion.content_size'
                        )
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
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextEntry::make(
                            'currentVersion.content_url'
                        )
                            ->label('Content Locator')
                            ->copyable()
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make(
                            'currentVersion.checksum'
                        )
                            ->label('SHA-256 Checksum')
                            ->copyable()
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make(
                            'currentVersion.released_at'
                        )
                            ->label('Released At')
                            ->dateTime()
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 4,
                            ]),

                        TextEntry::make(
                            'currentVersion.effective_from'
                        )
                            ->label('Effective From')
                            ->dateTime()
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 4,
                            ]),

                        TextEntry::make(
                            'next_review_date'
                        )
                            ->label('Next Review')
                            ->date()
                            ->placeholder('-')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 4,
                            ]),
                    ]),
            ]);
    }
}
