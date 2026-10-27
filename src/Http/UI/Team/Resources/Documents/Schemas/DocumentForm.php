<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Team\Resources\Documents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Rimba\Dms\Enums\DocumentStatus;
use Rimba\Dms\Enums\SecurityClassification;
use Rimba\Dms\Models\Document;
use Rimba\Dms\Models\DocumentCategory;

class DocumentForm
{
    public static function configure(
        Schema $schema,
    ): Schema {
        return $schema
            ->components([
                Tabs::make('Document')
                    ->persistTabInQueryString()
                    ->tabs([
                        static::generalTab(),
                        static::controlTab(),
                        static::lifecycleTab(),
                        static::initialVersionTab(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected static function generalTab(): Tab
    {
        return Tab::make('General')
            ->icon(Heroicon::OutlinedDocumentText)
            ->schema([
                Section::make('Document Identity')
                    ->description(
                        'Core information used to identify and classify the document.'
                    )
                    ->columns(12)
                    ->schema([
                        TextInput::make('doc_number')
                            ->label('Document Number')
                            ->placeholder('SOP-001')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 6,
                            ]),

                        Select::make('document_type')
                            ->label('Document Type')
                            ->options(fn (): array => config('bites.dms.document_types', []))
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required()
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        Select::make('category_id')
                            ->label('Category')
                            ->relationship(
                                'category',
                                'name',
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn (
                                    DocumentCategory $record,
                                ): string => filled($record->code)
                                    ? "{$record->code} - {$record->name}"
                                    : $record->name
                            )
                            ->searchable([
                                'code',
                                'name',
                            ])
                            ->preload()
                            ->native(false)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 4,
                            ]),

                        Select::make('parent_id')
                            ->label('Parent Document')
                            ->relationship(
                                'parent',
                                'title',
                                modifyQueryUsing: fn ($query) => $query->orderBy('doc_number'),
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn (
                                    Document $record,
                                ): string => "{$record->doc_number} - {$record->title}"
                            )
                            ->searchable([
                                'doc_number',
                                'title',
                            ])
                            ->preload()
                            ->native(false)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 4,
                            ]),

                        TextInput::make('site_location')
                            ->label('Site / Location')
                            ->placeholder(
                                'MY, Plant 1, HQ, etc.'
                            )
                            ->maxLength(255)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 4,
                            ]),
                    ]),

                Section::make('Ownership')
                    ->description(
                        'Assign organizational and individual accountability.'
                    )
                    ->columns(12)
                    ->schema([
                        Select::make('team_id')
                            ->label('Owning Team')
                            ->relationship(
                                'team',
                                'name',
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 4,
                            ]),

                        Select::make('owner_id')
                            ->label('Document Owner')
                            ->relationship(
                                'owner',
                                'name',
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 4,
                            ]),

                        Select::make('author_id')
                            ->label('Author')
                            ->relationship(
                                'author',
                                'name',
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 4,
                            ]),
                    ]),
            ]);
    }

    protected static function controlTab(): Tab
    {
        return Tab::make('Control')
            ->icon(Heroicon::OutlinedShieldCheck)
            ->schema([
                Section::make('Document Control')
                    ->columns(12)
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options(
                                DocumentStatus::options()
                            )
                            ->default(
                                DocumentStatus::Draft->value
                            )
                            ->required()
                            ->native(false)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        Select::make(
                            'security_classification'
                        )
                            ->label(
                                'Security Classification'
                            )
                            ->options(
                                SecurityClassification::options()
                            )
                            ->default(
                                SecurityClassification::Internal
                                    ->value
                            )
                            ->required()
                            ->native(false)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        Toggle::make('is_controlled')
                            ->label('Controlled Document')
                            ->default(true)
                            ->inline(false)
                            ->columnSpan([
                                'default' => 6,
                                'lg' => 2,
                            ]),

                        Toggle::make('requires_training')
                            ->label('Requires Training')
                            ->default(false)
                            ->inline(false)
                            ->columnSpan([
                                'default' => 6,
                                'lg' => 2,
                            ]),

                        TextInput::make(
                            'retention_period_years'
                        )
                            ->label('Retention Years')
                            ->numeric()
                            ->minValue(0)
                            ->default(5)
                            ->suffix('years')
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 2,
                            ]),

                        Textarea::make(
                            'regulatory_impact'
                        )
                            ->label('Regulatory Impact')
                            ->rows(4)
                            ->columnSpanFull(),

                        KeyValue::make(
                            'risk_assessment_tags'
                        )
                            ->label(
                                'Risk Assessment Tags'
                            )
                            ->keyLabel('Risk / Tag')
                            ->valueLabel('Description')
                            ->addActionLabel(
                                'Add risk tag'
                            )
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    protected static function lifecycleTab(): Tab
    {
        return Tab::make('Lifecycle')
            ->icon(Heroicon::OutlinedCalendarDays)
            ->schema([
                Section::make('Lifecycle Dates')
                    ->description(
                        'These values are normally controlled by document lifecycle actions.'
                    )
                    ->columns(12)
                    ->schema([
                        DatePicker::make('approved_date')
                            ->label('Approved Date')
                            ->native(false)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        DatePicker::make('effective_date')
                            ->label('Effective Date')
                            ->native(false)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        DatePicker::make(
                            'next_review_date'
                        )
                            ->label('Next Review Date')
                            ->native(false)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        DatePicker::make('obsolete_date')
                            ->label('Obsolete Date')
                            ->native(false)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextInput::make(
                            'currentVersion.version'
                        )
                            ->label(
                                'Current Released Version'
                            )
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder(
                                'No released version'
                            )
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 3,
                            ]),

                        TextInput::make(
                            'regulatory_hash'
                        )
                            ->label('Regulatory Hash')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan([
                                'default' => 12,
                                'lg' => 9,
                            ]),
                    ]),
            ]);
    }

    protected static function initialVersionTab(): Tab
    {
        return Tab::make('Initial Content')
            ->icon(Heroicon::OutlinedDocumentPlus)
            ->visible(
                fn (?Document $record): bool => ! $record instanceof Document
            )
            ->schema([
                Section::make('Initial Document Version')
                    ->description(
                        'The document and its initial Version 1.0.0 will be created together.'
                    )
                    ->columns(12)
                    ->schema(
                        DocumentVersionForm::components(
                            prefix: 'version',
                            initial: true,
                        )
                    ),
            ]);
    }
}
