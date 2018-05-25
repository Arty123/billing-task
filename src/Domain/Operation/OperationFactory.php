<?php

declare(strict_types=1);

namespace App\Domain\Operation;

class OperationFactory
{
    public function createOperation(array $data): OperationInterface
    {
        return new Operation(
            $data[OperationConstant::AMOUNT_INDEX_NAME],
            $data[OperationConstant::TYPE_INDEX_NAME],
            $data[OperationConstant::TID_INDEX_NAME],
            $data[OperationConstant::SENDER_INDEX_NAME],
            $data[OperationConstant::RECIPIENT_INDEX_NAME]
        );
    }
}
