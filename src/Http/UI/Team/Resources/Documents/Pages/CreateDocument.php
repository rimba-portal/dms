<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Team\Resources\Documents\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Document\Http\UI\Team\Resources\Documents\DocumentResource;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected static ?string $title = 'New Document';

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Document created';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl(
            'view',
            ['record' => $this->record],
        );
    }
}
