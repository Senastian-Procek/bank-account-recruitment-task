<?php
declare(strict_types=1);

namespace App\Test\BankAccount\Unit\Model\Policy;

use App\BankAccount\Model\Enum\Currency;
use App\BankAccount\Model\Policy\DebitCosts\StandardDebitCosts;
use App\BankAccount\Model\VO\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class StandardDebitsCostsTest extends TestCase
{

    public static function amountsProvider(): array
    {
        return [
            [1000, 5],
            [9999, 50],
            [10000, 50],
            [333, 2],
            [1, 0],
        ];
    }

    #[DataProvider('amountsProvider')]
    public function testShouldCheckFeeCalculation(int $amount, $expectedFee)
    {
        $policy = new StandardDebitCosts();
        $money = new Money($amount, Currency::USD);
        $fee = $policy->getFee($money);

        $this->assertEquals($expectedFee, $fee->amount);
    }

    public static function incorrectAmountsProvider(): array
    {
        return [
            [0, 0],
            [-1000, 0],
        ];
    }

    #[DataProvider('incorrectAmountsProvider')]
    public function testShouldThrowExceptionOnIncorrectAmount(int $amount, $expectedFee)
    {
        $this->expectExceptionMessage('Amount should be greater than 0.');
        $policy = new StandardDebitCosts();
        $money = new Money($amount, Currency::USD);
        $fee = $policy->getFee($money);

        $this->assertEquals($expectedFee, $fee->amount);
    }
}
