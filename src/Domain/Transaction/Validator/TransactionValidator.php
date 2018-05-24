<?php

namespace App\Domain\Transaction\Validator;

use App\Domain\Operation\OperationInterface;
use App\Entity\AccountingTransaction;
use App\Exception\DuplicateTransactionIdException;
use Doctrine\ORM\EntityManagerInterface;

class TransactionValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validateTransaction(OperationInterface $operation): bool
    {
        $transaction = $this->em
            ->getRepository(AccountingTransaction::class)
            ->findOneBy(['tid' => $operation->getTid()]);

        if ($transaction) {
            throw new DuplicateTransactionIdException('Duplicate transaction_id(' . $operation->getTid() . ')');
        }

        return true;
    }
}