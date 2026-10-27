<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Team\Resources\Documents\Pages;

use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Dms\Http\UI\Team\Resources\Documents\Actions\AddVersionAction;
use Rimba\Dms\Http\UI\Team\Resources\Documents\DocumentResource;

class EditDocument extends EditRecord
{
    protected static string $resource =
        DocumentResource::class;

    protected static ?string $title =
        'Edit Document Metadata';

    protected function getHeaderActions(): array
    {
        return [
            AddVersionAction::make(),

            ViewAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Document metadata updated';
    }
}
