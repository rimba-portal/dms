<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentCategorys;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentCategorys\Pages\ListDocumentCategorys;
use Rimba\Dms\Models\DocumentCategory;
use UnitEnum;

class DocumentCategoryResource extends Resource
{
    protected static ?string $model = DocumentCategory::class;

    protected static string|UnitEnum|null $navigationGroup = 'Dms';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play';

    protected static ?int $navigationSort = 76;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([]);
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
            'index' => ListDocumentCategorys::route('/'),
            // 'create' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentCategorys\Pages\CreateDocumentCategory::route('/create'),
            // 'view' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentCategorys\Pages\ViewDocumentCategory::route('/{record}'),
            // 'edit' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentCategorys\Pages\EditDocumentCategory::route('/{record}/edit'),
            //
        ];
    }
}
