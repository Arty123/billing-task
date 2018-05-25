<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Validator;

use App\Domain\Operation\OperationInterface;
use App\Entity\AccountingTransaction;
use App\Entity\TransactionType;
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

    public function validateTransactionType(OperationInterface $operation): bool
    {
        $transaction = $this->em
            ->getRepository(TransactionType::class)
            ->findOneBy(['name' => $operation->getType()]);

        if ($transaction) {
            throw new DuplicateTransactionIdException('Not allowed transaction with name '. $operation->getType());
        }

        return true;
    }
}
