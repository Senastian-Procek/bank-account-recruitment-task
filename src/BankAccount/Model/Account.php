<?php
declare(strict_types=1);

namespace App\BankAccount\Model;

use App\BankAccount\Model\Enum\Currency;
use App\BankAccount\Model\Enum\Operation;
use App\BankAccount\Model\Exception\UnknownOperationException;
use App\BankAccount\Model\Policy\DebitCosts\DebitCostsPolicy;
use App\BankAccount\Model\Policy\DebitLimits\DebitLimitsPolicy;
use App\BankAccount\Model\Policy\DebitOverdraft\DebitOverdraftPolicy;
use App\BankAccount\Model\VO\Balance;
use App\BankAccount\Model\VO\Money;
use Webmozart\Assert\Assert;


class Account
{
    private function __construct(
        private readonly DebitCostsPolicy     $debitCostsPolicy,
        private readonly DebitLimitsPolicy    $debitLimitsPolicy,
        private readonly DebitOverdraftPolicy $debitOverdraftPolicy,
        private readonly Currency             $currency,
        private Balance                       $balance,
        private array                         $transactionsHistory = [],
    )
    {
    }

    static public function openAccount(
        Currency             $currency,
        DebitCostsPolicy     $debitCostsPolicy,
        DebitLimitsPolicy    $debitLimitsPolicy,
        DebitOverdraftPolicy $debitOverdraftPolicy,
    ): self
    {
        return new Account(
            debitCostsPolicy: $debitCostsPolicy,
            debitLimitsPolicy: $debitLimitsPolicy,
            debitOverdraftPolicy: $debitOverdraftPolicy,
            currency: $currency,
            balance: new Balance(new Money(0, $currency))
        );
    }

    public function credit(Money $money): void
    {
        Assert::greaterThan($money->amount, 0);
        $this->transactionsHistory[] = new Transaction(Operation::CREDIT, $money);
        $this->updateBalance();
    }

    public function debit(Money $money): void
    {
        Assert::greaterThan($money->amount, 0);
        $transactionFee = $this->debitCostsPolicy->getFee($money);

        $transactionInProgress = new Transaction(Operation::DEBIT, $money->add($transactionFee));

        $this->debitLimitsPolicy->check($this->transactionsHistory, $transactionInProgress);
        $this->debitOverdraftPolicy->check($this->balance->money, $transactionInProgress->money);

        $this->transactionsHistory[] = $transactionInProgress;

        $this->updateBalance();
    }

    public function currentBalance(): Balance
    {
        return $this->balance;
    }

    private function updateBalance(): void
    {
        $newBalanceMoneyAmount = 0;
        foreach ($this->transactionsHistory as $transaction) {
            /** @var Transaction $transaction */
            Assert::eq($this->currency, $transaction->money->currency);
            match ($transaction->operation) {
                Operation::CREDIT => $newBalanceMoneyAmount += $transaction->money->amount,
                Operation::DEBIT => $newBalanceMoneyAmount -= $transaction->money->amount,
                default => throw new UnknownOperationException($transaction->operation)
            };
        }

        $this->balance = new Balance(new Money($newBalanceMoneyAmount, $this->currency));
    }
}