<?php

declare(strict_types=1);

namespace Rimba\Document\Enums;

enum DocumentStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Suspended = 'suspended';
    case Withdrawn = 'withdrawn';
    case Obsolete = 'obsolete';
}
