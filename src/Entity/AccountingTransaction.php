<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity
 * @ORM\Table(name="accounting_transaction", uniqueConstraints={@UniqueConstraint(name="transaction_id", columns={"tid"})})
 */
class AccountingTransaction
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="type", type="string")
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(name="tid", type="string")
     */
    private $tid;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var Account|null

     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="user_id")
     */
    private $sender;

    /**
     * @var Account|null

     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumn(name="recipient_id", referencedColumnName="user_id")
     */
    private $recipient;

    /**
     * @var int
     *
     * @ORM\Column(name="create_at", type="datetime_immutable")
     */
    private $createAt;

    public function __construct(string $type, int $amount, string $tid, ?Account $sender, ?Account $recipient)
    {
        $this->type = $type;
        $this->tid = $tid;
        $this->amount = $amount;
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->createAt = new \DateTimeImmutable();
    }

    public function getSender(): ?Account
    {
        return $this->sender;
    }

    public function getRecipient(): ?Account
    {
        return $this->recipient;
    }
}
