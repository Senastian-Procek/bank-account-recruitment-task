<?php
declare(strict_types=1);

namespace App\BankAccount\Model\Policy\DebitOverdraft;

use App\BankAccount\Model\VO\Money;

interface DebitOverdraftPolicy
{
    public function check(Money $accountMoney, Money $transactionInProgressMoney) : void;
}