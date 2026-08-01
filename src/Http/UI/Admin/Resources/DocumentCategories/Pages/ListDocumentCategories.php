<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentCategories\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentCategories\DocumentCategoryResource;

class ListDocumentCategories extends ListRecords
{
    protected static string $resource = DocumentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
