<?php

declare(strict_types=1);

namespace App\Domain\Transaction;

use App\Domain\Operation\Decorator\UnblockOperationDecorator;
use App\Domain\Operation\OperationInterface;
use App\Entity\AccountingTransaction;
use App\Exception\UnblockAmountException;

class UnblockTransaction extends AbstractTransaction
{
    protected function transaction(OperationInterface $operation): void
    {
        $recipientAccount = $this->getAccount($operation->getRecipient());
        $blockedTransactions = $this->getBlockedTransactions($operation->getRecipient());
        $sumOfBlockedTransactions = $this->getSumOfBlockedTransactionsForRecipient($blockedTransactions);

        if ($operation->getAmount() > $sumOfBlockedTransactions) {
            throw new UnblockAmountException('Trying to unblock amount greater than it was blocked before');
        }

        /** @var AccountingTransaction $transaction */
        foreach ($blockedTransactions as $transaction) {
            $transaction->markAsUnblock();
        }

        var_dump($operation);
        $unblockOperation = new UnblockOperationDecorator($operation, $sumOfBlockedTransactions);
        var_dump($unblockOperation);
        $accountingTransaction = $this->accountingFactory
            ->createAccountingTransaction($unblockOperation, null, $recipientAccount);

        $accountingEntry = $this->accountingFactory
            ->createAccountingEntry($recipientAccount, $accountingTransaction, $sumOfBlockedTransactions);

        $recipientAccount->calculateBalance($sumOfBlockedTransactions);

        $this->em->persist($accountingTransaction);
        $this->em->persist($accountingEntry);
    }

    private function getSumOfBlockedTransactionsForRecipient(array $blockedTransactions): int
    {
        $sum = 0;

        /** @var AccountingTransaction $blockedTransaction */
        foreach ($blockedTransactions as $blockedTransaction) {
            $sum += $blockedTransaction->getAmount();
        }

        return $sum;
    }

    private function getBlockedTransactions(int $userId): array
    {
        return $this->em
            ->getRepository(AccountingTransaction::class)
            ->getBlockedTransactions($userId);
    }
}
