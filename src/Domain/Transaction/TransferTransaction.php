<?php

declare(strict_types=1);

namespace App\Domain\Transaction;

use App\Domain\Operation\OperationInterface;
use App\Exception\NotEnoughBalanceException;

class TransferTransaction extends AbstractTransaction
{
    protected function transaction(OperationInterface $operation): void
    {
        $senderAccount = $this->getAccount($operation->getSender());

        if ($senderAccount->getBalance() < $operation->getAmount()) {
            throw new NotEnoughBalanceException('User with userId = ' . $senderAccount->getUserId() . '  hasn\'t enough money');
        }

        $recipientAccount = $this->getAccount($operation->getRecipient());

        $accountingTransaction = $this->accountingFactory
            ->createAccountingTransaction($operation, $senderAccount, $recipientAccount);

        $accountingEntrySender = $this->accountingFactory
            ->createAccountingEntry($senderAccount, $accountingTransaction, $this->getNegativeAmount($operation->getAmount()));
        $senderAccount->calculateBalance($accountingEntrySender->getAmount());

        $accountingEntryRecipient = $this->accountingFactory
            ->createAccountingEntry($recipientAccount, $accountingTransaction, $operation->getAmount());
        $recipientAccount->calculateBalance($accountingEntryRecipient->getAmount());

        $this->em->persist($accountingTransaction);
        $this->em->persist($accountingEntrySender);
        $this->em->persist($accountingEntryRecipient);
    }
}
