<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentApprovals\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentApprovals\DocumentApprovalResource;

class ListDocumentApprovals extends ListRecords
{
    protected static string $resource = DocumentApprovalResource::class;

    protected static ?string $title = 'Document Approvals';

    protected ?string $subheading = 'Manage workflows, workflow histories, and reviewer comments for pending files.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
