<?php

declare(strict_types=1);

namespace App\Domain\Transaction;

use App\Domain\Operation\OperationInterface;
use App\Entity\Account;
use App\Entity\AccountingEntry;
use App\Entity\AccountingTransaction;

class AccountingFactory
{
    public function createAccountingTransaction(
        OperationInterface $operation,
        Account $senderAccount = null,
        Account $recipientAccount = null
    ): AccountingTransaction {
        return new AccountingTransaction(
            $operation->getType(),
            $operation->getAmount(),
            $operation->getTid(),
            $senderAccount,
            $recipientAccount
        );
    }

    public function createAccountingEntry(
        Account $account,
        AccountingTransaction $transaction,
        int $amount
    ): AccountingEntry {
        return new AccountingEntry($account, $transaction, $amount);
    }
}
