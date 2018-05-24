<?php

namespace App\Tests\Transaction;

use App\Domain\Operation\OperationFactory;
use App\Domain\Transaction\BlockTransaction;
use App\Entity\Account;

class BlockTransactionTest extends AbstractTransaction
{
    /**
     * @dataProvider dataForProcessTest
     *
     * @param  Account|null $account
     * @param int $expectedPersistCount
     * @param bool $actualResult
     */
    public function testProcess(?Account $account, int $expectedPersistCount, bool $actualResult): void
    {
        $this->accountRepository->method('getAccountForUpdate')->willReturn($account);
        $this->em->expects($this->exactly($expectedPersistCount))->method('persist');

        $blockTransaction = new BlockTransaction($this->em, $this->logger,  $this->transactionValidator, $this->accountingFactory);

        $testData = ['recipientId' => 1, 'tid' => 'randomString', 'billingType' => 'block', 'amount' => 100];

        $result = $blockTransaction->process((new OperationFactory())->createOperation($testData));

        $this->assertEquals($result, $actualResult);
    }

    public function dataForProcessTest(): \Generator
    {
        yield 'No account' => [null, 0, false];
        yield 'Not enough balance' => [new Account(1, 99), 0, false];
        yield 'Success' => [new Account(1, 200), 2, true];
    }
}