<?php
declare(strict_types=1);

namespace App\Test\BankAccount\Unit\Model;

use App\BankAccount\Model\Account;
use App\BankAccount\Model\Enum\Currency;
use App\BankAccount\Model\Exception\BalanceAccountIsToLowException;
use App\BankAccount\Model\Exception\DailyAccountDebitTransactionLimitExceededException;
use App\BankAccount\Model\Policy\DebitCosts\StandardDebitCosts;
use App\BankAccount\Model\Policy\DebitLimits\StandardDebitLimits;
use App\BankAccount\Model\Policy\DebitOverdraft\StandardDebitOverdraft;
use App\BankAccount\Model\VO\Money;
use App\Test\BankAccount\Provider\AccountProvider;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class AccountTest extends TestCase
{

    public function testShouldCreateNewAccount()
    {
        $account = Account::openAccount(
            Currency::EUR,
            new StandardDebitCosts(),
            new StandardDebitLimits(),
            new StandardDebitOverdraft()
        );
        $this->assertInstanceOf(Account::class, $account);
    }

    public function testShouldCreditToAccount()
    {
        $account = AccountProvider::createCommonAccount();

        $account->credit(new Money(1112, Currency::EUR));
        $this->assertEquals(1112, $account->currentBalance()->money->amount);
        $account->credit(new Money(3, Currency::EUR));
        $this->assertEquals(1115, $account->currentBalance()->money->amount);
        $account->credit(new Money(100, Currency::EUR));
        $this->assertEquals(1215, $account->currentBalance()->money->amount);
    }

    public function testShouldNotCreditToAccountWhenCurrencyIsOther()
    {
        $account = AccountProvider::createCommonAccount();

        $this->expectException(InvalidArgumentException::class);
        $account->credit(new Money(1112, Currency::USD));

    }

    public function testShouldSimplyDebitToAccount()
    {
        $account = AccountProvider::createCommonAccount();
        $account->credit(new Money(1000000, Currency::EUR));

        $account->debit(new Money(3, Currency::EUR));
        $this->assertEquals(999997, $account->currentBalance()->money->amount);
    }

    public function testShouldOverdraftWhileDebit()
    {
        $account = AccountProvider::createCommonAccount();
        $account->credit(new Money(1000000, Currency::EUR));

        $this->expectException(BalanceAccountIsToLowException::class);
        $account->debit(new Money(1000000, Currency::EUR));

    }

    public function testShouldExceedDailyDebitTransactionLimit()
    {
        $account = AccountProvider::createCommonAccount();
        $account->credit(new Money(1000000, Currency::EUR));

        $this->expectException(DailyAccountDebitTransactionLimitExceededException::class);
        $account->debit(new Money(1000, Currency::EUR));

        $this->assertEquals(998995, $account->currentBalance()->money->amount);
        $account->debit(new Money(1000, Currency::EUR));

        $this->assertEquals(997990, $account->currentBalance()->money->amount);
        $account->debit(new Money(1000, Currency::EUR));

        $this->assertEquals(996985, $account->currentBalance()->money->amount);
        $account->debit(new Money(1000, Currency::EUR));


        $this->fail('Should not reach this line');
    }
}
