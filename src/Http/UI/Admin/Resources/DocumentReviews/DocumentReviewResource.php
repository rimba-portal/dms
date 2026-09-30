<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentReviews;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentReviews\Pages\ListDocumentReviews;
use Rimba\Dms\Models\DocumentReview;
use UnitEnum;

class DocumentReviewResource extends Resource
{
    protected static ?string $model = DocumentReview::class;

    protected static string|UnitEnum|null $navigationGroup = 'Dms';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play';

    protected static ?int $navigationSort = 79;

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
            'index' => ListDocumentReviews::route('/'),
            // 'create' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentReviews\Pages\CreateDocumentReview::route('/create'),
            // 'view' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentReviews\Pages\ViewDocumentReview::route('/{record}'),
            // 'edit' => \Rimba\Dms\Http\UI\Admin\Resources\DocumentReviews\Pages\EditDocumentReview::route('/{record}/edit'),
            //
        ];
    }
}
