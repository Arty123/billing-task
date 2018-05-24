<?php

declare(strict_types=1);

namespace App\Domain\Transaction;

use App\Domain\Operation\OperationInterface;
use App\Entity\AccountingEntry;
use App\Entity\AccountingTransaction;
use App\Exception\NotEnoughBalance;

class TransferTransaction extends AbstractTransaction
{
    protected function transaction(OperationInterface $operation): void
    {
        $senderAccount = $this->getAccount($operation->getSender());

        if ($senderAccount->getBalance() < $operation->getAmount()) {
            throw new NotEnoughBalance('User with userId = ' . $senderAccount->getUserId() . '  hasn\'t enough money');
        }

        $recipientAccount = $this->getAccount($operation->getRecipient());

        $accountingTransaction = new AccountingTransaction(
            $operation->getType(),
            $operation->getAmount(),
            $operation->getTid(),
            $senderAccount,
            $recipientAccount
        );

        $accountingEntrySender = new AccountingEntry(
            $senderAccount,
            $accountingTransaction,
            $this->getNegativeAmount($operation->getAmount())
        );
        $senderAccount->calculateBalance($accountingEntrySender->getAmount());

        $accountingEntryRecipient = new AccountingEntry(
            $recipientAccount,
            $accountingTransaction,
            $operation->getAmount());
        $recipientAccount->calculateBalance($accountingEntryRecipient->getAmount());

        $this->em->persist($accountingTransaction);
        $this->em->persist($accountingEntrySender);
        $this->em->persist($accountingEntryRecipient);
    }
}
