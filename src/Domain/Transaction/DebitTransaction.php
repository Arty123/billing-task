<?php

declare(strict_types=1);

namespace App\Domain\Transaction;

use App\Domain\Operation\OperationInterface;
use App\Exception\NotEnoughBalanceException;

class DebitTransaction extends AbstractTransaction
{
    protected function transaction(OperationInterface $operation): void
    {
        $senderAccount = $this->getAccount($operation->getSender());

        if ($senderAccount->getBalance() < $operation->getAmount()) {
            throw new NotEnoughBalanceException('Account with userId = ' . $senderAccount->getUserId() . '  hasn\'t enough balance');
        }

        $accountingTransaction = $this->accountingFactory
            ->createAccountingTransaction($operation, $senderAccount, null);

        $accountingEntry = $this->accountingFactory
            ->createAccountingEntry($senderAccount, $accountingTransaction, $this->getNegativeAmount($operation->getAmount()));

        $senderAccount->calculateBalance($accountingEntry->getAmount());

        $this->em->persist($accountingTransaction);
        $this->em->persist($accountingEntry);
    }
}
