<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Team\Resources\Documents\Actions;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Rimba\Dms\Actions\CreateDocumentVersion;
use Rimba\Dms\Http\UI\Team\Resources\Documents\Schemas\DocumentVersionForm;
use Rimba\Dms\Models\Document;

class AddVersionAction
{
    public static function make(
        string $name = 'addVersion',
    ): Action {
        return Action::make($name)
            ->label('Add Version')
            ->icon('heroicon-o-document-plus')
            ->color('primary')
            ->modalHeading('Add Document Version')
            ->modalDescription(
                'Upload new content or reference external content.'
            )
            ->modalSubmitActionLabel(
                'Create Version'
            )
            ->schema(
                DocumentVersionForm::components(
                    prefix: 'version',
                    initial: false,
                )
            )
            ->action(
                function (
                    Document $record,
                    array $data,
                ): void {
                    $versionData =
                        DocumentVersionForm::mutateVersionData(
                            $data['version'] ?? [],
                        );

                    $version = app(
                        CreateDocumentVersion::class
                    )->execute(
                        document: $record,
                        versionData: $versionData,
                    );

                    Notification::make()
                        ->success()
                        ->title(
                            'Document version created'
                        )
                        ->body(
                            "Version {$version->version} was created as a draft."
                        )
                        ->send();
                }
            );
    }
}
