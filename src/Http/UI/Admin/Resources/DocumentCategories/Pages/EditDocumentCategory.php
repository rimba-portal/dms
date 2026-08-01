<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentCategories\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentCategories\DocumentCategoryResource;

class EditDocumentCategory extends EditRecord
{
    protected static string $resource = DocumentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
