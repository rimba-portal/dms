<?php

declare(strict_types=1);

namespace Rimba\Dms\Actions;

use Illuminate\Support\Facades\DB;
use Rimba\Dms\Events\DocumentCreated;
use Rimba\Dms\Models\Document;
use Rimba\Versioning\Enums\VersionIncrementType;
use Rimba\Versioning\Models\Version;

class CreateDocument
{
    public function __construct(
        protected CreateDocumentVersion $createDocumentVersion,
    ) {}

    public function execute(
        array $documentData,
        array $versionData,
    ): Document {

        return DB::transaction(
            function () use (
                $documentData,
                $versionData,
            ): Document {

                $document = Document::create(
                    $documentData
                );

                $version = $this
                    ->createDocumentVersion
                    ->execute(
                        document: $document,

                        versionData: [
                            ...$versionData,

                            /*
                             * Initial version
                             */

                            'revision_type' => VersionIncrementType::Major,
                        ],
                    );

                /*
                 * optional:
                 * point to latest draft
                 */

                $document->update([
                    'current_version_id' => $version->id,
                ]);

                event(
                    new DocumentCreated(
                        $document
                    )
                );

                return $document->refresh();
            }
        );
    }
}
