<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentApprovals;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentApprovals\Pages\ListDocumentApprovals;
use Rimba\Dms\Models\DocumentApproval;
use UnitEnum;

class DocumentApprovalResource extends Resource
{
    protected static ?string $model = DocumentApproval::class;

    protected static string|UnitEnum|null $navigationGroup = 'Dms';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play';

    protected static ?int $navigationSort = 74;

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
            'index' => ListDocumentApprovals::route('/'),
            // 'create' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentApprovals\Pages\CreateDocumentApproval::route('/create'),
            // 'view' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentApprovals\Pages\ViewDocumentApproval::route('/{record}'),
            // 'edit' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentApprovals\Pages\EditDocumentApproval::route('/{record}/edit'),
            //
        ];
    }
}
