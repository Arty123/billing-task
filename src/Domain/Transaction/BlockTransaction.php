<?php

declare(strict_types=1);

namespace App\Domain\Transaction;

use App\Domain\Operation\OperationInterface;
use App\Exception\NotEnoughBalanceException;

class BlockTransaction extends AbstractTransaction
{
    protected function transaction(OperationInterface $operation): void
    {
        $recipientAccount = $this->getAccount($operation->getRecipient());

        if ($recipientAccount->getBalance() < $operation->getAmount()) {
            throw new NotEnoughBalanceException('Account with userId = ' . $recipientAccount->getUserId() . '  hasn\'t enough balance');
        }

        $accountingTransaction = $this->accountingFactory
            ->createAccountingTransaction($operation, null, $recipientAccount);

        $accountingEntry = $this->accountingFactory
            ->createAccountingEntry($recipientAccount, $accountingTransaction, $this->getNegativeAmount($operation->getAmount()));

        $recipientAccount->calculateBalance($accountingEntry->getAmount());

        $this->em->persist($accountingTransaction);
        $this->em->persist($accountingEntry);
    }
}
