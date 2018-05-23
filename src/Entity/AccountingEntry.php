<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="accounting_entry")
 */
class AccountingEntry
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
     * @var Account

     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="user_id")
     */
    private $account;

    /**
     * @var AccountingTransaction
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\AccountingTransaction")
     * @ORM\JoinColumn(name="transaction_id", referencedColumnName="id")
     */
    private $transaction;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="create_at", type="datetime_immutable")
     */
    private $createAt;

    public function __construct(Account $account, AccountingTransaction $transaction, int $amount)
    {
        $this->account = $account;
        $this->transaction = $transaction;
        $this->amount = $amount;
        $this->createAt = new \DateTimeImmutable();
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
