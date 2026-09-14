<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Team\Resources\Documents\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Rimba\Dms\Actions\CreateDocument as CreateDocumentAction;
use Rimba\Dms\Http\UI\Team\Resources\Documents\DocumentResource;
use Rimba\Dms\Http\UI\Team\Resources\Documents\Schemas\DocumentVersionForm;

class CreateDocument extends CreateRecord
{
    protected static string $resource =
        DocumentResource::class;

    protected static ?string $title =
        'Create Document';

    protected function handleRecordCreation(
        array $data,
    ): Model {
        $versionData = Arr::pull(
            $data,
            'version',
            [],
        );

        $versionData =
            DocumentVersionForm::mutateVersionData(
                $versionData,
            );

        return app(CreateDocumentAction::class)
            ->execute(
                documentData: $data,
                versionData: $versionData,
            );
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Document and initial version created';
    }
}
