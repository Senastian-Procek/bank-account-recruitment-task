<?php
declare(strict_types=1);

namespace App\BankAccount\Model\Policy\DebitLimits;


use App\BankAccount\Model\Enum\Operation;
use App\BankAccount\Model\Exception\DailyAccountDebitTransactionLimitExceededException;
use App\BankAccount\Model\Transaction;
use Webmozart\Assert\Assert;

class StandardDebitLimits implements DebitLimitsPolicy
{
    private const TODAY_FORMAT = 'Ymd';
    private const DAILY_OPERATION_LIMIT = 3;

    public function check(array $transactionsHistory, Transaction $transactionInProgress): void
    {
        Assert::allIsInstanceOf($transactionsHistory, Transaction::class);
        $todaysOperations = $this->getTodaysDebitTransactions($transactionsHistory);

        if (count($todaysOperations)+1 > self::DAILY_OPERATION_LIMIT) {
            throw new DailyAccountDebitTransactionLimitExceededException();
        }
    }

    public function getTodaysDebitTransactions(array $transactionsHistory): array
    {
        $today = (new \DateTimeImmutable())->format(self::TODAY_FORMAT);

        return array_filter(
            $transactionsHistory,
            fn(Transaction $transaction) =>
                $transaction->isDebit() && $today === $transaction->created->format(self::TODAY_FORMAT)
        );
    }
}