<?php

declare(strict_types=1);

namespace App\Domain\Transaction;

use App\Domain\Operation\OperationInterface;

class BlockTransaction extends AbstractTransaction
{
    protected function transaction(OperationInterface $operation): void
    {
        $recipientAccount = $this->getAccount($operation->getRecipient());

        $accountingTransaction = $this->accountingFactory
            ->createAccountingTransaction($operation, null, $recipientAccount);

        $accountingEntry = $this->accountingFactory
            ->createAccountingEntry($recipientAccount, $accountingTransaction, $this->getNegativeAmount($operation->getAmount()));

        $recipientAccount->calculateBalance($accountingEntry->getAmount());

        $this->em->persist($accountingTransaction);
        $this->em->persist($accountingEntry);
    }
}
