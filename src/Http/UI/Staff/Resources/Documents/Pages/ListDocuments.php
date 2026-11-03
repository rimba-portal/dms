<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Staff\Resources\Documents\Pages;

use Filament\Resources\Pages\ListRecords;
use Rimba\Document\Http\UI\Staff\Resources\Documents\DocumentResource;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected static ?string $title = 'Documents';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
