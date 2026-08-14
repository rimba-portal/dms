<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\Documents;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Rimba\Dms\Http\UI\Admin\Resources\Documents\Pages\CreateDocument;
use Rimba\Dms\Http\UI\Admin\Resources\Documents\Pages\EditDocument;
use Rimba\Dms\Http\UI\Admin\Resources\Documents\Pages\ListDocuments;
use Rimba\Dms\Http\UI\Admin\Resources\Documents\Pages\ViewDocument;
use Rimba\Dms\Http\UI\Admin\Resources\Documents\Schemas\DocumentForm;
use Rimba\Dms\Http\UI\Admin\Resources\Documents\Schemas\DocumentInfolist;
use Rimba\Dms\Http\UI\Admin\Resources\Documents\Tables\DocumentsTable;
use Rimba\Dms\Models\Document;
use UnitEnum;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static string|UnitEnum|null $navigationGroup = 'Versioning';

    protected static string|BackedEnum|null $navigationIcon = 'bites-dms';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return DocumentForm::configure($schema);
    }

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
            'create' => CreateDocument::route('/create'),
            'view' => ViewDocument::route('/{record}'),
            'edit' => EditDocument::route('/{record}/edit'),
        ];
    }
}
