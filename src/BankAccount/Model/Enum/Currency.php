<?php
declare(strict_types=1);

namespace App\BankAccount\Model\Enum;

enum Currency
{
    case PLN;
    case USD;
    case EUR;
}