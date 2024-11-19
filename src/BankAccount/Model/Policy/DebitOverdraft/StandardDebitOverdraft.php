<?php
declare(strict_types=1);

namespace App\BankAccount\Model\Policy\DebitOverdraft;


use App\BankAccount\Model\Exception\BalanceAccountIsToLowException;
use App\BankAccount\Model\VO\Money;

class StandardDebitOverdraft implements DebitOverdraftPolicy
{
    public function check(Money $accountMoney, Money $transactionInProgressMoney) : void
    {
        if($accountMoney->amount < $transactionInProgressMoney->amount){
            throw new BalanceAccountIsToLowException();
        }
    }
}