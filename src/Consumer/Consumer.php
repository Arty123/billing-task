<?php

declare(strict_types=1);

namespace App\Consumer;

use App\Domain\Operation\OperationConstant;
use App\Domain\Operation\OperationFactory;
use App\Domain\Transaction\AbstractTransaction;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class Consumer implements ConsumerInterface
{
    public const RECIPIENT_INDEX_NAME = 'recipientId';
    public const SENDER_INDEX_NAME = 'senderId';
    public const TYPE_INDEX_NAME = OperationConstant::OPERATION_MSG_LABEL;
    public const AMOUNT_INDEX_NAME = 'amount';
    public const TID_INDEX_NAME = 'tid';
    public const COUNT_INDEX_NAME = 'countQuery';
    public const MAX_COUNT_QUERY = 5;

    /**
     * @var OperationFactory
     */
    private $operationFactory;

    /**
     * @var AbstractTransaction
     */
    private $transaction;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(OperationFactory $operationsFactory, AbstractTransaction $transaction, LoggerInterface $logger)
    {
        $this->operationFactory = $operationsFactory;
        $this->transaction = $transaction;
        $this->logger = $logger;
    }

    public function execute(AMQPMessage $msg)
    {
        try {
            $body = unserialize($msg->getBody());
            $operation = $this->operationFactory->createOperation($body);

            $this->transaction->process($operation);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
