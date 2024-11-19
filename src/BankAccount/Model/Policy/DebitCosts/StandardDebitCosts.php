<?php
declare(strict_types=1);

namespace App\BankAccount\Model\Policy\DebitCosts;

use App\BankAccount\Model\VO\Money;
use Webmozart\Assert\Assert;

class StandardDebitCosts implements DebitCostsPolicy
{
    private const PERCENTAGE = 0.5;

    public function getFee(Money $money) : Money
    {
        Assert::greaterThan($money->amount, 0, 'Amount should be greater than 0.');
        $feeAmount = (int) round($money->amount * (self::PERCENTAGE/100));
        return new Money($feeAmount, $money->currency);
    }
}