<?php
declare(strict_types=1);

namespace App\Test\BankAccount\Provider;

use App\BankAccount\Model\Account;
use App\BankAccount\Model\Enum\Currency;
use App\BankAccount\Model\Policy\DebitCosts\StandardDebitCosts;
use App\BankAccount\Model\Policy\DebitLimits\StandardDebitLimits;
use App\BankAccount\Model\Policy\DebitOverdraft\StandardDebitOverdraft;

class AccountProvider
{
    static public function createCommonAccount(): Account
    {
        return Account::openAccount(
            Currency::EUR,
            new StandardDebitCosts(),
            new StandardDebitLimits(),
            new StandardDebitOverdraft(),
        );
    }
}