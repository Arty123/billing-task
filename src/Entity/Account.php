<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Entity\Repository\AccountRepository")
 * @ORM\Table(name="account")
 */
class Account
{
    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="balance", type="integer", options={"default":0})
     */
    private $balance;

    /**
     * @var int
     *
     * @ORM\Version
     * @ORM\Column(type="integer")
     */
    private $version;

    public function __construct(int $userId, int $balance)
    {
        $this->userId = $userId;
        $this->balance = $balance;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function calculateBalance(int $balance): void
    {
        $this->balance = $this->balance + $balance;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
