<?php
declare(strict_types=1);

namespace App\Test\BankAccount\Unit\Model\VO;

use App\BankAccount\Model\Enum\Currency;
use App\BankAccount\Model\VO\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function testShouldCheckCurrencyRestriction()
    {
        $this->expectExceptionMessage('Currencies don\'t match, they should be the same.');
        $money1 = new Money(1000, Currency::USD);
        $money2 = new Money(1000, Currency::EUR);

        $money1->add($money2);
    }
}
