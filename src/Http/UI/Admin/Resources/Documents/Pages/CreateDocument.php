<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\Documents\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Dms\Http\UI\Admin\Resources\Documents\DocumentResource;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;
}
