<?php

declare(strict_types=1);

namespace App\Domain\Transaction;

use App\Domain\Operation\OperationInterface;
use App\Domain\Transaction\Validator\TransactionValidator;
use App\Entity\Account;
use App\Exception\UserDoesNotExistException;
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

    /**
     * @var TransactionValidator
     */
    private $validator;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, TransactionValidator $validator)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->validator = $validator;
    }

    public function process(OperationInterface $operation)
    {
        $this->validator->validateTransaction($operation);
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
        $account = $this->em->getRepository(Account::class)->getAccountForUpdate($userId);

        if (!$account) {
            throw new UserDoesNotExistException('User\'s account with userId = ' . $userId . '  doesn\'t exist');
        }

        return $account;
    }

    protected function getNegativeAmount(int $amount): int
    {
        return -$amount;
    }
}
