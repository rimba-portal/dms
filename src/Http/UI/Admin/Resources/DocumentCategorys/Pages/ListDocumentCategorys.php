<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentCategorys\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentCategorys\DocumentCategoryResource;

class ListDocumentCategorys extends ListRecords
{
    protected static string $resource = DocumentCategoryResource::class;

    protected static ?string $title = 'Document Categories';

    protected ?string $subheading = 'Organize the document system structure with custom codes and descriptions.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
