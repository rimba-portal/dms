<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentTrainings\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentTrainings\DocumentTrainingResource;

class ListDocumentTrainings extends ListRecords
{
    protected static string $resource = DocumentTrainingResource::class;

    protected static ?string $title = 'Training Prerequisites';

    protected ?string $subheading = 'Link specific document versions to mandatory training modules and courses.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
