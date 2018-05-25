<?php

declare(strict_types=1);

namespace App\Domain\Operation;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

class Operation implements OperationInterface
{
    /**
     * @var int
     *
     * @Assert\NotBlank(message="operation.amount.not_blank")
     * @Assert\Type(type="int", message="operation.amount.integer")
     */
    private $amount;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="operation.type.not_blank")
     * @Assert\Type(type="string", message="operation.type.string")
     */
    private $type;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="operation.tid.not_blank")
     * @Assert\Type(type="string", message="operation.tid.string")
     */
    private $tid;

    /**
     * @var int|null
     * @AppAssert\IntegerNullConstraint
     */
    private $sender;

    /**
     * @var int|null
     * @AppAssert\IntegerNullConstraint
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
