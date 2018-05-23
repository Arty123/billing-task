<?php

declare(strict_types=1);

namespace App\Domain\Operation;

final class OperationConstant
{
    public const OPERATION_MSG_LABEL = 'billingType';

    public const DEPOSIT = 'deposit';
    public const DEBIT = 'debit';
    public const TRANSFER = 'transfer';
}
