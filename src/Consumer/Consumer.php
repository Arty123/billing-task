<?php

declare(strict_types=1);

namespace App\Consumer;

use App\Domain\Operation\OperationFactory;
use App\Domain\Transaction\AbstractTransaction;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class Consumer implements ConsumerInterface
{
    public const MAX_COUNT_QUERY = 5;
    public const COUNT_INDEX_NAME = 'countQuery';

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
    /**
     * @var ProducerInterface
     */
    private $delayedProducer;

    public function __construct(OperationFactory $operationsFactory, AbstractTransaction $transaction, LoggerInterface $logger, ProducerInterface $delayedProducer)
    {
        $this->operationFactory = $operationsFactory;
        $this->transaction = $transaction;
        $this->logger = $logger;
        $this->delayedProducer = $delayedProducer;
    }

    public function execute(AMQPMessage $msg)
    {
        try {
            $body = unserialize($msg->getBody());
            $operation = $this->operationFactory->createOperation($body);

            if (isset($body[self::COUNT_INDEX_NAME]) && $body[self::COUNT_INDEX_NAME] > self::MAX_COUNT_QUERY) {
                return true;
            }

            if (!$this->transaction->process($operation)) {
                $body[self::COUNT_INDEX_NAME] = ($body[self::COUNT_INDEX_NAME] ?? 1) + 1;
                $this->delayedProducer->publish(serialize($body), $operation->getType());
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
