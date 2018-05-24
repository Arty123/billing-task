<?php

declare(strict_types=1);

namespace App\Domain\Operation\Decorator;

use App\Domain\Operation\OperationInterface;

class UnblockOperationDecorator implements OperationInterface
{
    /**
     * @var int
     */
    private $amount;
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $tid;
    /**
     * @var int|null
     */
    private $sender;
    /**
     * @var int|null
     */
    private $recipient;

    public function __construct(OperationInterface $operation, int $sumOfBlockedTransactions)
    {
        $this->amount = $sumOfBlockedTransactions;
        $this->type = $operation->getType();
        $this->sender = $operation->getSender();
        $this->recipient = $operation->getRecipient();
        $this->tid = $operation->getTid();
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTid(): string
    {
        return $this->tid;
    }

    /**
     * @return int|null
     */
    public function getSender(): ?int
    {
        return $this->sender;
    }

    /**
     * @return int|null
     */
    public function getRecipient(): ?int
    {
        return $this->recipient;
    }
}
