<?php

namespace App\Enums;

enum LoanStatus: string
{
    case Draft       = 'draft';
    case Submitted   = 'submitted';
    case UnderReview = 'under_review';
    case Approved    = 'approved';
    case Rejected    = 'rejected';
    case Closed      = 'closed';

    public function label(): string
    {
        return match($this) {
            LoanStatus::Draft       => 'Draft',
            LoanStatus::Submitted   => 'Submitted',
            LoanStatus::UnderReview => 'Under Review',
            LoanStatus::Approved    => 'Approved',
            LoanStatus::Rejected    => 'Rejected',
            LoanStatus::Closed      => 'Closed',
        };
    }

    public function color(): string
    {
        return match($this) {
            LoanStatus::Draft       => 'gray',
            LoanStatus::Submitted   => 'blue',
            LoanStatus::UnderReview => 'yellow',
            LoanStatus::Approved    => 'green',
            LoanStatus::Rejected    => 'red',
            LoanStatus::Closed      => 'purple',
        };
    }
}
