<?php
declare(strict_types=1);

namespace App\BankAccount\Model\VO;

readonly class Balance
{

    public function __construct(public Money $money)
    {
    }
}