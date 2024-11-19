<?php
declare(strict_types=1);

namespace App\BankAccount\Model\Enum;

enum Operation
{
    case CREDIT;
    case DEBIT;
}
