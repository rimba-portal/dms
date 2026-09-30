<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentDistributions\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentDistributions\DocumentDistributionResource;

class ListDocumentDistributions extends ListRecords
{
    protected static string $resource = DocumentDistributionResource::class;

    protected static ?string $title = 'Document Distributions';

    protected ?string $subheading = 'View logs showing which teams and staff members received specific versions.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
