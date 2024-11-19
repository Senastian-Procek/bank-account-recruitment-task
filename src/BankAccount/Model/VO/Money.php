<?php
declare(strict_types=1);

namespace App\BankAccount\Model\VO;

use App\BankAccount\Model\Enum\Currency;
use Webmozart\Assert\Assert;

readonly class Money
{
    public function __construct(
        public int      $amount,
        public Currency $currency,
    )
    {
    }

    public function add(Money $money): Money
    {
        Assert::eq($money->currency, $this->currency, 'Currencies don\'t match, they should be the same.');
        return new Money($this->amount + $money->amount, $this->currency);
    }
}