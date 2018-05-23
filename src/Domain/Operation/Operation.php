<?php

declare(strict_types=1);

namespace App\Domain\Operation;

class Operation implements OperationInterface
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

    public function __construct(int $amount, string $type, string $tid, ?int $sender, ?int $recipient)
    {
        $this->amount = $amount;
        $this->type = $type;
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->tid = $tid;
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
