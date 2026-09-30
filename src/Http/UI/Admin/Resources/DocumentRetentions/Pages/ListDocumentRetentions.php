<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentRetentions\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentRetentions\DocumentRetentionResource;

class ListDocumentRetentions extends ListRecords
{
    protected static string $resource = DocumentRetentionResource::class;

    protected static ?string $title = 'Document Retentions';

    protected ?string $subheading = 'Track file lifespan limits, retention status, and archive disposal dates.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
