<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Team\Resources\Documents\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Document\Http\UI\Team\Resources\Documents\DocumentResource;

class ListDocuments extends ListRecords
{
    protected static string $resource =
        DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Document')
                ->icon('heroicon-o-document-plus'),
        ];
    }
}
