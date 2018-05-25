<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Operation\OperationConstant;
use App\Entity\TransactionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TransactionTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach (OperationConstant::TYPES as $type) {
            $transactionType = new TransactionType($type);
            $manager->persist($transactionType);
        }
        $manager->flush();
    }
}
