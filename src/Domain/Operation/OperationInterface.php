<?php

declare(strict_types=1);

namespace App\Domain\Operation;

interface OperationInterface
{
    /**
     * @return int
     */
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
