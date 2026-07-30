<?php

declare(strict_types=1);

namespace Rimba\Dms\Traits;

trait HasAcknowledgements
{
    public function pendingAcknowledgements()
    {
        return $this->acknowledgements()->whereNull('acknowledged_at');
    }
}
