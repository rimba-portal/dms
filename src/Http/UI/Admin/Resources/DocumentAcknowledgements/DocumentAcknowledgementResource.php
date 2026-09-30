<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentAcknowledgements;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentAcknowledgements\Pages\ListDocumentAcknowledgements;
use Rimba\Dms\Models\DocumentAcknowledgement;
use UnitEnum;

class DocumentAcknowledgementResource extends Resource
{
    protected static ?string $model = DocumentAcknowledgement::class;

    protected static string|UnitEnum|null $navigationGroup = 'Dms';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play';

    protected static ?int $navigationSort = 73;

    protected static ?string $recordTitleAttribute = 'id';

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
            'index' => ListDocumentAcknowledgements::route('/'),
            // 'create' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentAcknowledgements\Pages\CreateDocumentAcknowledgement::route('/create'),
            // 'view' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentAcknowledgements\Pages\ViewDocumentAcknowledgement::route('/{record}'),
            // 'edit' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentAcknowledgements\Pages\EditDocumentAcknowledgement::route('/{record}/edit'),
            //
        ];
    }
}
