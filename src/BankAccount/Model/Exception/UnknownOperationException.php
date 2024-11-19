<?php
declare(strict_types=1);

namespace App\BankAccount\Model\Exception;

use App\BankAccount\Model\Enum\Operation;
use Throwable;


class UnknownOperationException extends DomainException
{
    public function __construct(Operation $operation, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Unknown operation: {$operation->name}", $code, $previous);
    }


}