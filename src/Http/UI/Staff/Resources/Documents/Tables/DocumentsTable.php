<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Staff\Resources\Documents\Tables;

use Rimba\Dms\Http\UI\Staff\Resources\Documents\DocumentResource;
use Rimba\Base\Support\LinkViewResolver;
use Bites\Service\Helpers\AttachmentLinkResolver;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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
