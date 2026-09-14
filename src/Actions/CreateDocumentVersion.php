<?php

declare(strict_types=1);

namespace Rimba\Dms\Actions;

use Illuminate\Support\Facades\DB;
use Rimba\Dms\Models\Document;
use Rimba\Versioning\Enums\VersionStatus;
use Rimba\Versioning\Models\Version;

class CreateDocumentVersion
{
    public function execute(
        Document $document,
        array $versionData,
    ): Version {
        return DB::transaction(
            function () use (
                $document,
                $versionData,
            ): Version {

                return $document
                    ->versions()
                    ->create([
                        ...$versionData,

                        'status' => VersionStatus::Draft,
                    ]);
            }
        );
    }
}
