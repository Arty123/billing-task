<?php

declare(strict_types=1);

namespace App\Domain\Operation;

class OperationFactory
{
    public function createOperation(array $data): OperationInterface
    {
        if (!isset($data[OperationConstant::OPERATION_MSG_LABEL]) || empty($data[OperationConstant::OPERATION_MSG_LABEL])) {
            throw new \Exception('No information about operation type');
        }

        switch ($data[OperationConstant::OPERATION_MSG_LABEL]) {
            case OperationConstant::DEPOSIT:
                $operation = $this->createDepositOperation($data);
                break;
            case OperationConstant::DEBIT:
                $operation = $this->createDebitOperation($data);
                break;
            case OperationConstant::TRANSFER:
                $operation = $this->createTransferOperation($data);
                break;
            case OperationConstant::BLOCK:
                $operation = $this->createBlockOperation($data);
                break;
            case OperationConstant::UNBLOCK:
                $operation = $this->createUnblockOperation($data);
                break;
            default:
                throw new \Exception('Can\'t resolve operation type');
        }

        return $operation;
    }

    private function createTransferOperation(array $data): OperationInterface
    {
        return new Operation(
            $data[OperationConstant::AMOUNT_INDEX_NAME],
            $data[OperationConstant::TYPE_INDEX_NAME],
            $data[OperationConstant::TID_INDEX_NAME],
            $data[OperationConstant::SENDER_INDEX_NAME],
            $data[OperationConstant::RECIPIENT_INDEX_NAME]
        );
    }

    private function createDepositOperation(array $data): OperationInterface
    {
        return new Operation(
            $data[OperationConstant::AMOUNT_INDEX_NAME],
            $data[OperationConstant::TYPE_INDEX_NAME],
            $data[OperationConstant::TID_INDEX_NAME],
            null,
            $data[OperationConstant::RECIPIENT_INDEX_NAME]
        );
    }

    private function createDebitOperation(array $data): OperationInterface
    {
        return new Operation(
            $data[OperationConstant::AMOUNT_INDEX_NAME],
            $data[OperationConstant::TYPE_INDEX_NAME],
            $data[OperationConstant::TID_INDEX_NAME],
            $data[OperationConstant::SENDER_INDEX_NAME],
            null
        );
    }

    private function createBlockOperation(array $data): OperationInterface
    {
        return new Operation(
            $data[OperationConstant::AMOUNT_INDEX_NAME],
            $data[OperationConstant::TYPE_INDEX_NAME],
            $data[OperationConstant::TID_INDEX_NAME],
            null,
            $data[OperationConstant::RECIPIENT_INDEX_NAME]
        );
    }

    private function createUnblockOperation(array $data): OperationInterface
    {
        return new Operation(
            $data[OperationConstant::AMOUNT_INDEX_NAME],
            $data[OperationConstant::TYPE_INDEX_NAME],
            $data[OperationConstant::TID_INDEX_NAME],
            null,
            $data[OperationConstant::RECIPIENT_INDEX_NAME]
        );
    }
}
