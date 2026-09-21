<?php

namespace App\Enums;

enum LoanType: string
{
    case DSCR         = 'dscr';
    case Bridge       = 'bridge';
    case SBA          = 'sba';
    case CMBS         = 'cmbs';
    case Conventional = 'conventional';

    public function label(): string
    {
        return match($this) {
            LoanType::DSCR         => 'DSCR',
            LoanType::Bridge       => 'Bridge Loan',
            LoanType::SBA          => 'SBA',
            LoanType::CMBS         => 'CMBS',
            LoanType::Conventional => 'Conventional',
        };
    }
}
