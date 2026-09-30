<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentTrainings;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentTrainings\Pages\ListDocumentTrainings;
use Rimba\Dms\Models\DocumentTraining;
use UnitEnum;

class DocumentTrainingResource extends Resource
{
    protected static ?string $model = DocumentTraining::class;

    protected static string|UnitEnum|null $navigationGroup = 'Dms';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play';

    protected static ?int $navigationSort = 81;

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
            'index' => ListDocumentTrainings::route('/'),
            // 'create' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentTrainings\Pages\CreateDocumentTraining::route('/create'),
            // 'view' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentTrainings\Pages\ViewDocumentTraining::route('/{record}'),
            // 'edit' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentTrainings\Pages\EditDocumentTraining::route('/{record}/edit'),
            //
        ];
    }
}
