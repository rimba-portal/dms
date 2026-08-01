<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\Documents\Schemas;

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
use Rimba\Dms\Models\DocumentCategory;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Document Details')
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('General')
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                                Section::make('Document Identity')
                                    ->description('Core document information used for control, search, and traceability.')
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

                                        Select::make('dms_document_type')
                                            ->label('Document Type')
                                            ->options(fn (): array => config('rimba_dms.document_types', []))
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 3,
                                            ]),

                                        Select::make('category_id')
                                            ->label('Category')
                                            ->relationship('category', 'name')
                                            ->getOptionLabelFromRecordUsing(
                                                fn (DocumentCategory $record): string => filled($record->code)
                                                    ? sprintf('%s - %s', $record->code, $record->name)
                                                    : $record->name
                                            )
                                            ->searchable(['code', 'name'])
                                            ->preload()
                                            ->native(false)
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 4,
                                            ]),

                                        Select::make('parent_id')
                                            ->label('Parent Document')
                                            ->relationship('parent', 'title')
                                            ->searchable(['doc_number', 'title'])
                                            ->preload()
                                            ->native(false)
                                            ->helperText('Use this for document families, superseded hierarchy, or grouped procedures.')
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 4,
                                            ]),

                                        TextInput::make('site_location')
                                            ->label('Site / Location')
                                            ->placeholder('MY, Plant 1, HQ, etc.')
                                            ->maxLength(255)
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 4,
                                            ]),
                                    ]),

                                Section::make('Ownership')
                                    ->description('Assign document accountability and organizational ownership.')
                                    ->columns(12)
                                    ->schema([
                                        Select::make('team_id')
                                            ->label('Owning Team')
                                            ->relationship('team', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->native(false)
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 4,
                                            ]),

                                        Select::make('owner_id')
                                            ->label('Document Owner')
                                            ->relationship('owner', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->native(false)
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 4,
                                            ]),

                                        Select::make('author_id')
                                            ->label('Author')
                                            ->relationship('author', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->native(false)
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 4,
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Control')
                            ->icon(Heroicon::OutlinedShieldCheck)
                            ->schema([
                                Section::make('Document Control')
                                    ->description('Status, control, classification, and training requirements.')
                                    ->columns(12)
                                    ->schema([
                                        Select::make('status')
                                            ->label('Status')
                                            ->options(DocumentStatus::options())
                                            ->default(DocumentStatus::Draft->value)
                                            ->required()
                                            ->native(false)
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 3,
                                            ]),

                                        Select::make('security_classification')
                                            ->label('Security Classification')
                                            ->options(SecurityClassification::options())
                                            ->default(SecurityClassification::Internal->value)
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

                                        TextInput::make('retention_period_years')
                                            ->label('Retention Years')
                                            ->numeric()
                                            ->minValue(0)
                                            ->default(5)
                                            ->suffix('years')
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 2,
                                            ]),

                                        Textarea::make('regulatory_impact')
                                            ->label('Regulatory Impact')
                                            ->placeholder('Describe applicable standard, clause, customer requirement, or compliance impact.')
                                            ->rows(4)
                                            ->columnSpan(12),

                                        KeyValue::make('risk_assessment_tags')
                                            ->label('Risk Assessment Tags')
                                            ->keyLabel('Risk / Tag')
                                            ->valueLabel('Description')
                                            ->addActionLabel('Add risk tag')
                                            ->columnSpan(12),
                                    ]),
                            ]),

                        Tab::make('Lifecycle')
                            ->icon(Heroicon::OutlinedCalendarDays)
                            ->schema([
                                Section::make('Lifecycle Dates')
                                    ->description('Track approval, release, review, and obsolescence dates.')
                                    ->columns(12)
                                    ->schema([
                                        DatePicker::make('approved_date')
                                            ->label('Approved Date')
                                            ->native(false)
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 3,
                                            ]),

                                        DatePicker::make('effective_date')
                                            ->label('Effective Date')
                                            ->native(false)
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 3,
                                            ]),

                                        DatePicker::make('next_review_date')
                                            ->label('Next Review Date')
                                            ->native(false)
                                            ->helperText('Used by review due reminders.')
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 3,
                                            ]),

                                        DatePicker::make('obsolete_date')
                                            ->label('Obsolete Date')
                                            ->native(false)
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 3,
                                            ]),
                                    ]),

                                Section::make('Traceability')
                                    ->description('System-generated traceability information.')
                                    ->columns(12)
                                    ->schema([
                                        Select::make('current_version_id')
                                            ->label('Current Version')
                                            ->relationship('currentVersion', 'version')
                                            ->searchable()
                                            ->preload()
                                            ->native(false)
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 4,
                                            ]),

                                        TextInput::make('regulatory_hash')
                                            ->label('Regulatory Hash')
                                            ->disabled()
                                            ->dehydrated(false)
                                            ->maxLength(64)
                                            ->columnSpan([
                                                'default' => 12,
                                                'lg' => 8,
                                            ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
