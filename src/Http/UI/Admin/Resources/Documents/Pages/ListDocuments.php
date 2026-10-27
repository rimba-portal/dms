<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Admin\Resources\Documents\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Document\Http\UI\Admin\Resources\Documents\DocumentResource;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected static ?string $title = 'Controlled Documents';

    protected ?string $subheading = 'Track, review, and control official documents, standard operating procedures, and compliance files.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
