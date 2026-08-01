<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentCategories\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentCategories\DocumentCategoryResource;

class CreateDocumentCategory extends CreateRecord
{
    protected static string $resource = DocumentCategoryResource::class;
}
