<?php

namespace App\Tests\Account;

use App\Entity\Account;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public const TEST_VALUE = 5;

    public function testCalculateBalance()
    {
        $account = new Account(1, 0);
        $account->calculateBalance(self::TEST_VALUE);

        $this->assertEquals($account->getBalance(), self::TEST_VALUE);
    }
}
