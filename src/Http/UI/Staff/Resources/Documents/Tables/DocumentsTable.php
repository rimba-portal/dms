<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Staff\Resources\Documents\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Rimba\Base\Support\LinkViewResolver;
use Rimba\Document\Http\UI\Staff\Resources\Documents\DocumentResource;

class DocumentsTable
{
    // use HasRecordLinks;

    public static function configure(Table $table): Table
    {
        return LinkViewResolver::attach($table,
            DocumentResource::class
        )
            ->columns([
                TextColumn::make('file_name'),
                TextColumn::make('type'),
            ]);
    }
}
