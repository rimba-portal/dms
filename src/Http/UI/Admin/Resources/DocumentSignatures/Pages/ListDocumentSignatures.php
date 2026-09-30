<?php

declare(strict_types=1);

namespace Rimba\Dms\Http\UI\Admin\Resources\DocumentSignatures\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Dms\Http\UI\Admin\Resources\DocumentSignatures\DocumentSignatureResource;

class ListDocumentSignatures extends ListRecords
{
    protected static string $resource = DocumentSignatureResource::class;

    protected static ?string $title = 'Cryptographic Signatures';

    protected ?string $subheading = 'Audit digital verification hashes and explicit signing intent logs.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
