<?php

declare(strict_types=1);

namespace App\Entity\Repository;

use App\Entity\Account;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;

class AccountRepository extends EntityRepository
{
    /**
     * @param int $userId
     *
     * @return Account|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function getAccountForUpdate(int $userId): ?Account
    {
        $qb = $this->createQueryBuilder('a')
        ->select('a')
        ->where('a.userId = :userId')
        ->setParameter(':userId', $userId);

        $qb = $qb->getQuery()->setLockMode(LockMode::PESSIMISTIC_WRITE);

        return $qb->getOneOrNullResult();
    }
}
