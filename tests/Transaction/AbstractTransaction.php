<?php

namespace App\Tests\Transaction;

use App\Domain\Operation\OperationFactory;
use App\Domain\Transaction\AccountingFactory;
use App\Domain\Transaction\Validator\TransactionValidator;
use App\Entity\Repository\AccountingTransactionRepository;
use App\Entity\Repository\AccountRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AbstractTransaction extends TestCase
{
    /**
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @var AccountingTransactionRepository
     */
    protected $accountingTransactionRepository;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var AccountingFactory
     */
    protected $accountingFactory;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var TransactionValidator
     */
    protected $transactionValidator;

    public function setUp()
    {
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $this->accountingFactory = $this->getMockBuilder(AccountingFactory::class)->getMock();

        $this->connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $this->connection->method('beginTransaction')->willReturn(true);
        $this->connection->method('commit')->willReturn(true);
        $this->connection->method('rollBack')->willReturn(true);

        $this->accountRepository = $this->getMockBuilder(AccountRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAccountForUpdate'])->getMock();

        $this->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $this->em->method('getRepository')->willReturn($this->accountRepository);
        $this->em->method('getConnection')->willReturn($this->connection);

        $this->transactionValidator = $this->getMockBuilder(TransactionValidator::class)
            ->disableOriginalConstructor()->getMock();

        $this->transactionValidator->method('validateTransaction')->willReturn(true);
    }
}