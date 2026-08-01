<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentCategories;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentCategories\Pages\CreateDocumentCategory;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentCategories\Pages\EditDocumentCategory;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentCategories\Pages\ListDocumentCategories;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentCategories\Schemas\DocumentCategoryForm;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentCategories\Tables\DocumentCategoriesTable;
use Rimba\Dms\Models\DocumentCategory;

class DocumentCategoryResource extends Resource
{
    protected static ?string $model = DocumentCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DocumentCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentCategoriesTable::configure($table);
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
            'index' => ListDocumentCategories::route('/'),
            'create' => CreateDocumentCategory::route('/create'),
            'edit' => EditDocumentCategory::route('/{record}/edit'),
        ];
    }
}
