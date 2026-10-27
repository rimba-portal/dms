<?php

declare(strict_types=1);

namespace Rimba\Document\Http\UI\Team\Resources\Documents\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Rimba\Document\Http\UI\Team\Resources\Documents\Actions\AddVersionAction;
use Rimba\Document\Http\UI\Team\Resources\Documents\DocumentResource;

class ViewDocument extends ViewRecord
{
    protected static string $resource =
        DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            AddVersionAction::make(),

            EditAction::make()
                ->label('Edit Metadata'),
        ];
    }
}
