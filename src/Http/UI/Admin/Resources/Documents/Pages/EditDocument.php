<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\Documents\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Dms\Http\UI\Admin\Resources\Documents\DocumentResource;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
