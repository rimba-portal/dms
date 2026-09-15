<?php

declare(strict_types=1);

namespace Rimba\Dms\Actions;

use Illuminate\Support\Facades\DB;
use Rimba\Dms\Models\Document;
use Rimba\Versioning\Enums\VersionIncrementType;
use Rimba\Versioning\Enums\VersionStatus;
use Rimba\Versioning\Models\Version;

class CreateDocumentVersion
{
    public function execute(
        Document $document,
        array $versionData,
        VersionIncrementType $increment =
        VersionIncrementType::Patch,
    ): Version {

        return DB::transaction(
            function () use (
                $document,
                $versionData,
                $increment,
            ): Version {

                $version = new Version([
                    ...$versionData,

                    'status' => VersionStatus::Draft->value,
                ]);

                $version->setRevisionType(
                    $increment
                );

                $document
                    ->versions()
                    ->save($version);

                return $version->refresh();
            }
        );
    }
}
