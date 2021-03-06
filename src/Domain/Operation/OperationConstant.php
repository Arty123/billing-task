<?php

declare(strict_types=1);

namespace App\Domain\Operation;

final class OperationConstant
{
    public const OPERATION_MSG_LABEL = 'billingType';

    public const DEPOSIT = 'deposit';
    public const DEBIT = 'debit';
    public const TRANSFER = 'transfer';
    public const BLOCK = 'block';
    public const UNBLOCK = 'unblock';
    public const INACTIVE_BLOCK = 'inactive_block';

    public const TYPES = [
       self::DEPOSIT,
       self::DEBIT,
       self::TRANSFER,
       self::BLOCK,
       self::UNBLOCK,
       self::INACTIVE_BLOCK,
    ];

    public const SENDER_INDEX_NAME = 'senderId';
    public const TYPE_INDEX_NAME = self::OPERATION_MSG_LABEL;
    public const RECIPIENT_INDEX_NAME = 'recipientId';
    public const TID_INDEX_NAME = 'tid';
    public const AMOUNT_INDEX_NAME = 'amount';
}
