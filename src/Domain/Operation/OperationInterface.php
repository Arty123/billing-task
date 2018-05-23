<?php

declare(strict_types=1);

namespace App\Domain\Operation;

interface OperationInterface
{
    public const RECIPIENT_INDEX_NAME = 'recipientId';
    public const SENDER_INDEX_NAME = 'senderId';
    public const TYPE_INDEX_NAME = OperationConstant::OPERATION_MSG_LABEL;
    public const AMOUNT_INDEX_NAME = 'amount';
    public const TID_INDEX_NAME = 'tid';

    public function getAmount(): int;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getTid(): string;

    /**
     * @return int|null
     */
    public function getSender(): ?int;

    /**
     * @return int|null
     */
    public function getRecipient(): ?int;
}
