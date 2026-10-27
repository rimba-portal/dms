<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Staff\Resources\Documents\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Dms\Http\UI\Staff\Resources\Documents\DocumentResource;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('New Document'),
        ];
    }
}
