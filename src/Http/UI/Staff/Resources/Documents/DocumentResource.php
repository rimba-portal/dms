<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Staff\Resources\Documents;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Rimba\Document\Http\UI\Staff\Resources\Documents\Pages\ListDocuments;
use Rimba\Document\Http\UI\Staff\Resources\Documents\Pages\ViewDocument;
use Rimba\Document\Http\UI\Staff\Resources\Documents\Schemas\DocumentInfolist;
use Rimba\Document\Http\UI\Staff\Resources\Documents\Tables\DocumentsTable;
use Rimba\Document\Models\Document;
use UnitEnum;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static string|BackedEnum|null $navigationIcon = 'bites-s-dms';

    protected static string|UnitEnum|null $navigationGroup = 'Knowledge';

    protected static ?string $modelLabel = 'Documents';

    protected static ?string $recordTitleAttribute = 'title';

    public static function infolist(Schema $schema): Schema
    {
        return DocumentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocuments::route('/'),
            'view' => ViewDocument::route('/{record}'),
        ];
    }
}
