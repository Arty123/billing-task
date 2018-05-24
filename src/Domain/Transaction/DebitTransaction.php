<?php

declare(strict_types=1);

namespace App\Domain\Transaction;

use App\Domain\Operation\OperationInterface;
use App\Entity\AccountingEntry;
use App\Entity\AccountingTransaction;
use App\Exception\NotEnoughBalance;

class DebitTransaction extends AbstractTransaction
{
    protected function transaction(OperationInterface $operation): void
    {
        $senderAccount = $this->getAccount($operation->getSender());

        if ($senderAccount->getBalance() < $operation->getAmount()) {
            throw new NotEnoughBalance('Account with userId = ' . $senderAccount->getUserId() . '  hasn\'t enough balance');
        }

        $accountingTransaction = new AccountingTransaction(
            $operation->getType(),
            $operation->getAmount(),
            $operation->getTid(),
            $senderAccount,
            null
        );

        $accountingEntry = new AccountingEntry(
            $senderAccount,
            $accountingTransaction,
            $this->getNegativeAmount($operation->getAmount())
        );

        $senderAccount->calculateBalance($accountingEntry->getAmount());

        $this->em->persist($accountingTransaction);
        $this->em->persist($accountingEntry);
    }
}
