<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Team\Resources\Documents\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Rimba\Document\Models\Document;

final class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Document')
                    ->tabs([
                        Tab::make('General')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('Document Identity')
                                    ->description(
                                        'Define the identity and classification of the controlled document.',
                                    )
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('code')
                                            ->label('Document Number')
                                            ->required()
                                            ->maxLength(100)
                                            ->unique(
                                                table: Document::class,
                                                column: 'code',
                                                ignoreRecord: true,
                                            ),

                                        TextInput::make('title')
                                            ->required()
                                            ->maxLength(255),

                                        Select::make('document_type_id')
                                            ->label('Document Type')
                                            ->relationship(
                                                name: 'type',
                                                titleAttribute: 'name',
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->required(),

                                        Select::make('category_id')
                                            ->label('Category')
                                            ->relationship(
                                                name: 'category',
                                                titleAttribute: 'name',
                                            )
                                            ->searchable()
                                            ->preload(),

                                        Select::make('parent_id')
                                            ->label('Parent Document')
                                            ->relationship(
                                                name: 'parent',
                                                titleAttribute: 'title',
                                            )
                                            ->searchable()
                                            ->preload(),

                                        Textarea::make('description')
                                            ->rows(4)
                                            ->maxLength(2000)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Ownership')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                Section::make('Document Ownership')
                                    ->description(
                                        'Identify the organizational ownership and accountability for this document.',
                                    )
                                    ->columns(2)
                                    ->schema([
                                        Select::make('org_unit_id')
                                            ->label('Organizational Unit')
                                            ->relationship(
                                                name: 'orgUnit',
                                                titleAttribute: 'name',
                                            )
                                            ->searchable()
                                            ->preload(),

                                        Select::make('org_team_id')
                                            ->label('Owning Team')
                                            ->relationship(
                                                name: 'orgTeam',
                                                titleAttribute: 'name',
                                            )
                                            ->searchable()
                                            ->preload(),

                                        Select::make('owner_id')
                                            ->label('Document Owner')
                                            ->relationship(
                                                name: 'owner',
                                                titleAttribute: 'name',
                                            )
                                            ->searchable()
                                            ->preload(),

                                        Select::make('author_id')
                                            ->label('Author')
                                            ->relationship(
                                                name: 'author',
                                                titleAttribute: 'name',
                                            )
                                            ->searchable()
                                            ->preload(),
                                    ]),
                            ]),

                        Tab::make('Control')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Section::make('Document Control')
                                    ->description(
                                        'Define review, retention, security and training requirements.',
                                    )
                                    ->columns(2)
                                    ->schema([
                                        Select::make('security_classification')
                                            ->label('Security Classification')
                                            ->options([
                                                'public' => 'Public',
                                                'internal' => 'Internal',
                                                'confidential' => 'Confidential',
                                                'restricted' => 'Restricted',
                                            ])
                                            ->default('internal')
                                            ->required(),

                                        DatePicker::make('next_review_date')
                                            ->label('Next Review Date'),

                                        TextInput::make('retention_period')
                                            ->label('Retention Period')
                                            ->placeholder('For example: 7 years')
                                            ->maxLength(100),

                                        TagsInput::make('risk_tags')
                                            ->label('Risk Tags')
                                            ->placeholder(
                                                'Add risk or compliance tags',
                                            )
                                            ->columnSpanFull(),

                                        Checkbox::make('controlled')
                                            ->label('Controlled Document')
                                            ->default(true),

                                        Checkbox::make('requires_training')
                                            ->label('Training Required')
                                            ->default(false),

                                        Checkbox::make('regulatory_impact')
                                            ->label('Regulatory Impact')
                                            ->default(false),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
