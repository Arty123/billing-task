<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AccountFixtures extends Fixture
{
    public const COUNT_ACCOUNTS = 10;

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= self::COUNT_ACCOUNTS; ++$i) {
            $account = new Account($i, 0);
            $manager->persist($account);
        }
        $manager->flush();
    }
}
