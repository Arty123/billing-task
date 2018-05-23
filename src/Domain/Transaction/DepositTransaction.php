<?php

declare(strict_types=1);

namespace App\Domain\Transaction;

use App\Domain\Operation\OperationInterface;
use App\Entity\AccountingEntry;
use App\Entity\AccountingTransaction;

class DepositTransaction extends AbstractTransaction
{
    protected function transaction(OperationInterface $operation): void
    {
        $recipientAccount = $this->getAccount($operation->getRecipient());

        $accountingTransaction = new AccountingTransaction(
            $operation->getType(),
            $operation->getAmount(),
            $operation->getTid(),
            null,
            $recipientAccount
        );

        $accountingEntry = new AccountingEntry(
            $recipientAccount,
            $accountingTransaction,
            $operation->getAmount()
        );

        $recipientAccount->calculateBalance($accountingEntry->getAmount());

        $this->em->persist($accountingTransaction);
        $this->em->persist($accountingEntry);
    }
}
