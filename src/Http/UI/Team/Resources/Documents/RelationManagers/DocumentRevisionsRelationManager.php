<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Team\Resources\Documents\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentRevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';

    protected static ?string $title = 'Revisions';

    protected static ?string $modelLabel = 'Revision';

    protected static ?string $pluralModelLabel = 'Revisions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Revision')
                    ->description(
                        'Create or update a controlled revision of this document.',
                    )
                    ->columns(2)
                    ->schema([
                        TextInput::make('revision')
                            ->label('Revision')
                            ->placeholder('1.0')
                            ->required()
                            ->maxLength(50),

                        Select::make('source')
                            ->label('Content Source')
                            ->options([
                                'upload' => 'Upload Document',
                                'url' => 'External URL',
                            ])
                            ->default('upload')
                            ->required()
                            ->live(),

                        FileUpload::make('file_path')
                            ->label('Document File')
                            ->directory('documents/revisions')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->required(
                                fn (Get $get): bool => $get('source') === 'upload',
                            )
                            ->visible(
                                fn (Get $get): bool => $get('source') === 'upload',
                            )
                            ->columnSpanFull(),

                        TextInput::make('url')
                            ->label('Document URL')
                            ->placeholder('https://')
                            ->url()
                            ->required(
                                fn (Get $get): bool => $get('source') === 'url',
                            )
                            ->visible(
                                fn (Get $get): bool => $get('source') === 'url',
                            )
                            ->columnSpanFull(),

                        Textarea::make('change_summary')
                            ->label('Change Summary')
                            ->placeholder(
                                'Describe what is introduced or changed in this revision.',
                            )
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('revision')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('revision')
                    ->label('Revision')
                    ->badge()
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('source')
                    ->label('Source')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'upload' => 'Uploaded File',
                            'url' => 'External URL',
                            default => $state,
                        },
                    ),

                TextColumn::make('change_summary')
                    ->label('Change Summary')
                    ->limit(60)
                    ->wrap()
                    ->placeholder('Initial issue'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Revision')
                    ->icon('heroicon-o-document-plus'),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Edit'),

                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->emptyStateIcon('heroicon-o-document')
            ->emptyStateHeading('No revisions')
            ->emptyStateDescription(
                'Add the initial revision to begin document establishment.',
            );
    }
}
