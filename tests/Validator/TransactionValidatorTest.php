<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Domain\Operation\OperationFactory;
use App\Domain\Transaction\Validator\TransactionValidator;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class TransactionIdValidatorTest extends TestCase
{
    /**
     * @var TransactionValidator
     */
    private $validator;

    public function setUp()
    {
        $repository = $this->getMockBuilder(ObjectRepository::class)->getMock();
        $repository->expects($this->any())
            ->method('findOneBy')
            ->willReturn(null);
        $em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $em->method('getRepository')->willReturn($repository);
        $this->validator = new TransactionValidator($em);
    }

    /**
     * @param array $testData
     * @param bool $expected
     * @dataProvider getData
     */
    public function testValidate(array $testData, bool $expected)
    {
        $result = $this->validator->validateTransaction((new OperationFactory())->createOperation($testData));
        $this->assertEquals($expected, $result);
    }

    public function getData(): \Generator
    {
        yield 'No transactions in DB with the same transaction ID' => [
            ['senderId' => 1, 'tid' => 'randomString', 'recipientId' => 2, 'billingType' => 'debit', 'amount' => 100],
            true,
        ];
    }
}