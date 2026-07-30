<?php

declare(strict_types=1);

namespace Rimba\Dms\Services;

use Rimba\Dms\Models\Document;

class DocumentNumberService
{
    public function next(string $prefix, int $padding = 3): string
    {
        $latest = Document::query()
            ->where('doc_number', 'like', strtoupper($prefix).'-%')
            ->orderByDesc('id')
            ->value('doc_number');

        $sequence = $latest ? ((int) str($latest)->afterLast('-')->toString()) + 1 : 1;

        return sprintf('%s-%s', strtoupper($prefix), str_pad((string) $sequence, $padding, '0', STR_PAD_LEFT));
    }
}
