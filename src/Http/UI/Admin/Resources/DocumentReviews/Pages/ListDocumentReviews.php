<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentReviews\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentReviews\DocumentReviewResource;

class ListDocumentReviews extends ListRecords
{
    protected static string $resource = DocumentReviewResource::class;

    protected static ?string $title = 'Document Reviews';

    protected ?string $subheading = 'Manage scheduled compliance check dates, reviewer remarks, and review statuses.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
