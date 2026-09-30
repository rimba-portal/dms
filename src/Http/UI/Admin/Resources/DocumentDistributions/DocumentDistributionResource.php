<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentDistributions;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentDistributions\Pages\ListDocumentDistributions;
use Rimba\Dms\Models\DocumentDistribution;
use UnitEnum;

class DocumentDistributionResource extends Resource
{
    protected static ?string $model = DocumentDistribution::class;

    protected static string|UnitEnum|null $navigationGroup = 'Dms';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play';

    protected static ?int $navigationSort = 77;

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
            'index' => ListDocumentDistributions::route('/'),
            // 'create' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentDistributions\Pages\CreateDocumentDistribution::route('/create'),
            // 'view' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentDistributions\Pages\ViewDocumentDistribution::route('/{record}'),
            // 'edit' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentDistributions\Pages\EditDocumentDistribution::route('/{record}/edit'),
            //
        ];
    }
}
