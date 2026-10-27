<?php

declare(strict_types=1);

namespace Rimba\Document\Enums;

enum DocumentRevisionStatus: string
{
    case Draft = 'draft';
    case InReview = 'in_review';
    case Approved = 'approved';
    case Effective = 'effective';
    case Rejected = 'rejected';
    case Superseded = 'superseded';
    case Withdrawn = 'withdrawn';
}
