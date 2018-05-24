<?php

declare(strict_types=1);

namespace App\Domain\Transaction;

use App\Domain\Operation\OperationInterface;
use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractTransaction
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function process(OperationInterface $operation)
    {
        $this->em->getConnection()->beginTransaction();

        try {
            $this->transaction($operation);
            $this->em->flush();
            $this->em->getConnection()->commit();
            $this->em->clear();
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            $this->logger->error($e->getMessage());
        }
    }

    abstract protected function transaction(OperationInterface $operation): void;

    protected function getAccount($userId): Account
    {
        return $this->em->getRepository(Account::class)->getAccountForUpdate($userId);
    }

    protected function getNegativeAmount(int $amount): int
    {
        return -$amount;
    }
}
