<?php

declare(strict_types=1);

namespace App\Command;

use App\DataFixtures\AccountFixtures;
use App\Domain\Operation\OperationConstant;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:test-consumer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $producer = $this->getContainer()->get('old_sound_rabbit_mq.billing_producer');

        for ($i = 0; $i < 20; ++$i) {
            $testData = [
                OperationConstant::RECIPIENT_INDEX_NAME => rand(1, AccountFixtures::COUNT_ACCOUNTS),
                OperationConstant::OPERATION_MSG_LABEL => OperationConstant::DEPOSIT,
                OperationConstant::TID_INDEX_NAME => uniqid('', true),
                OperationConstant::AMOUNT_INDEX_NAME => 1000,
            ];

            $producer->publish(serialize($testData), OperationConstant::DEPOSIT);
        }

        $firstHalfAccounts = AccountFixtures::COUNT_ACCOUNTS / 2;
        $secondHalfAccounts = $firstHalfAccounts + 1;

        for ($i = 0; $i < 20; ++$i) {
            $testData = [
                OperationConstant::RECIPIENT_INDEX_NAME => rand(1, $firstHalfAccounts),
                OperationConstant::SENDER_INDEX_NAME => rand($secondHalfAccounts, AccountFixtures::COUNT_ACCOUNTS),
                OperationConstant::OPERATION_MSG_LABEL => OperationConstant::DEBIT,
                OperationConstant::TID_INDEX_NAME => uniqid('', true),
                OperationConstant::AMOUNT_INDEX_NAME => 10,
            ];

            $producer->publish(serialize($testData), OperationConstant::DEBIT);
        }

        for ($i = 0; $i < 20; ++$i) {
            $testData = [
                OperationConstant::RECIPIENT_INDEX_NAME => rand($secondHalfAccounts, AccountFixtures::COUNT_ACCOUNTS),
                OperationConstant::SENDER_INDEX_NAME => rand(1, $firstHalfAccounts),
                OperationConstant::OPERATION_MSG_LABEL => OperationConstant::TRANSFER,
                OperationConstant::AMOUNT_INDEX_NAME => 10,
                OperationConstant::TID_INDEX_NAME => uniqid('', true),
            ];

            $producer->publish(serialize($testData), OperationConstant::TRANSFER);
        }

        for ($i = 0; $i < 20; ++$i) {
            $testData = [
                OperationConstant::RECIPIENT_INDEX_NAME => rand(1, AccountFixtures::COUNT_ACCOUNTS),
                OperationConstant::SENDER_INDEX_NAME => null,
                OperationConstant::OPERATION_MSG_LABEL => OperationConstant::BLOCK,
                OperationConstant::AMOUNT_INDEX_NAME => 10,
                OperationConstant::TID_INDEX_NAME => uniqid('', true),
            ];

            $producer->publish(serialize($testData), OperationConstant::BLOCK);
        }
    }
}
