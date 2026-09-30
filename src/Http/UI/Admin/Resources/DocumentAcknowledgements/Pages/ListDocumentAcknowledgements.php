<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentAcknowledgements\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentAcknowledgements\DocumentAcknowledgementResource;

class ListDocumentAcknowledgements extends ListRecords
{
    protected static string $resource = DocumentAcknowledgementResource::class;

    protected static ?string $title = 'Document Acknowledgements';

    protected ?string $subheading = 'Monitor reader signatures and tracking records for released documents.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
