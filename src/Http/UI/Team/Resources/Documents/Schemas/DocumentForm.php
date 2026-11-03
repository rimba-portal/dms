<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Team\Resources\Documents\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Rimba\Document\Enums\DocumentStatus;
use Rimba\Document\Models\Document;

final class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Document')
                    ->description(
                        'Define the identity and ownership of the controlled document.',
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
                            ->label('Title')
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

                        Select::make('status')
                            ->label('Status')
                            ->options(
                                collect(DocumentStatus::cases())
                                    ->mapWithKeys(
                                        fn (DocumentStatus $status): array => [
                                            $status->value => str($status->value)
                                                ->headline()
                                                ->toString(),
                                        ],
                                    )
                                    ->all(),
                            )
                            ->default(DocumentStatus::Draft->value)
                            ->required()
                            ->disabledOn('create'),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->maxLength(5000)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
