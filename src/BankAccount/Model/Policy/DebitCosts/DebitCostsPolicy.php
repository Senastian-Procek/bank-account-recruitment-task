<?php
declare(strict_types=1);

namespace App\BankAccount\Model\Policy\DebitCosts;

use App\BankAccount\Model\VO\Money;

interface DebitCostsPolicy
{

    public function getFee(Money $money): Money;
}