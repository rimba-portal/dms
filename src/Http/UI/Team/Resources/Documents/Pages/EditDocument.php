<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Team\Resources\Documents\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Document\Http\UI\Team\Resources\Documents\DocumentResource;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected static ?string $title = 'Edit Document Metadata';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Document metadata updated';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl(
            'view',
            ['record' => $this->record],
        );
    }
}
