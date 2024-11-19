<?php
declare(strict_types=1);

namespace App\BankAccount\Model;

use App\BankAccount\Model\Enum\Operation;
use App\BankAccount\Model\VO\Money;

readonly class Transaction
{
    public \DateTimeImmutable $created;

    public function __construct(
        public Operation $operation,
        public Money     $money,
    )
    {
        $this->created = new \DateTimeImmutable();
    }

    public function isDebit(): bool
    {
        return Operation::DEBIT === $this->operation;
    }
}