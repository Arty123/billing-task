<?php

declare(strict_types=1);

namespace App\Entity\Repository;

use App\Domain\Operation\OperationConstant;
use Doctrine\ORM\EntityRepository;

class AccountingTransactionRepository extends EntityRepository
{
    /**
     * @param int $userId
     *
     * @return array
     */
    public function getBlockedTransactions(int $userId): array
    {
        $qb = $this->createQueryBuilder('accounting_transaction')
            ->select('accounting_transaction')
            ->where('accounting_transaction.recipient = :userId')
            ->andWhere('accounting_transaction.type = :type')
            ->setParameters([
                'userId' => $userId,
                'type' => OperationConstant::BLOCK,
            ]);

        return $qb->getQuery()->getResult();
    }
}
