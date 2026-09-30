<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentAttachments\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentAttachments\DocumentAttachmentResource;

class ListDocumentAttachments extends ListRecords
{
    protected static string $resource = DocumentAttachmentResource::class;

    protected static ?string $title = 'Document Attachments';

    protected ?string $subheading = 'Review digital file assets, system checksums, and storage paths.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
