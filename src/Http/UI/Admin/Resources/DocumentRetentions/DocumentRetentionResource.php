<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentRetentions;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentRetentions\Pages\ListDocumentRetentions;
use Rimba\Dms\Models\DocumentRetention;
use UnitEnum;

class DocumentRetentionResource extends Resource
{
    protected static ?string $model = DocumentRetention::class;

    protected static string|UnitEnum|null $navigationGroup = 'Dms';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play';

    protected static ?int $navigationSort = 78;

    protected static ?string $recordTitleAttribute = 'status';

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
            'index' => ListDocumentRetentions::route('/'),
            // 'create' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentRetentions\Pages\CreateDocumentRetention::route('/create'),
            // 'view' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentRetentions\Pages\ViewDocumentRetention::route('/{record}'),
            // 'edit' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentRetentions\Pages\EditDocumentRetention::route('/{record}/edit'),
            //
        ];
    }
}
