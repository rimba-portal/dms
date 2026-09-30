<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentSignatures;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentSignatures\Pages\ListDocumentSignatures;
use Rimba\Dms\Models\DocumentSignature;
use UnitEnum;

class DocumentSignatureResource extends Resource
{
    protected static ?string $model = DocumentSignature::class;

    protected static string|UnitEnum|null $navigationGroup = 'Dms';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play';

    protected static ?int $navigationSort = 80;

    protected static ?string $recordTitleAttribute = 'purpose';

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
            'index' => ListDocumentSignatures::route('/'),
            // 'create' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentSignatures\Pages\CreateDocumentSignature::route('/create'),
            // 'view' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentSignatures\Pages\ViewDocumentSignature::route('/{record}'),
            // 'edit' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentSignatures\Pages\EditDocumentSignature::route('/{record}/edit'),
            //
        ];
    }
}
