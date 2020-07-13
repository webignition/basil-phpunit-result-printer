<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\ExceptionData;

use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\UnknownExceptionData;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class UnknownExceptionDataTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $class, string $message)
    {
        $unknownExceptionData = new UnknownExceptionData($class, $message);

        self::assertSame($class, ObjectReflector::getProperty($unknownExceptionData, 'class'));
        self::assertSame($message, ObjectReflector::getProperty($unknownExceptionData, 'message'));
    }

    public function createDataProvider(): array
    {
        return [
            'default' => [
                'class' => 'Acme\ClassName',
                'message' => 'Unable to update widget when stationary'
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param UnknownExceptionData $unknownExceptionData
     * @param array<mixed> $expectedData
     */
    public function testGetData(UnknownExceptionData $unknownExceptionData, array $expectedData)
    {
        self::assertSame($expectedData, $unknownExceptionData->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'default' => [
                'unknownExceptionData' => new UnknownExceptionData(
                    'Acme\ClassName',
                    'Unable to update widget when stationary'
                ),
                'expectedData' => [
                    'type' => 'unknown',
                    'body' => [
                        'class' => 'Acme\ClassName',
                        'message' => 'Unable to update widget when stationary',
                    ],
                ]
            ],
        ];
    }
}
