<?php
declare(strict_types=1);

namespace App\BankAccount\Model\Policy\DebitLimits;

use App\BankAccount\Model\Transaction;

interface DebitLimitsPolicy
{

    public function check(array $transactionsHistory, Transaction $transactionInProgress): void;
}