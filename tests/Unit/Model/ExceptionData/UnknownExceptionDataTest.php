<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\ExceptionData;

use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\UnknownExceptionData;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;
use webignition\ObjectReflector\ObjectReflector;

class UnknownExceptionDataTest extends AbstractBaseTestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $class, string $message): void
    {
        $unknownExceptionData = new UnknownExceptionData($class, $message);

        self::assertSame($class, ObjectReflector::getProperty($unknownExceptionData, 'class'));
        self::assertSame($message, ObjectReflector::getProperty($unknownExceptionData, 'message'));
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        return [
            'default' => [
                'class' => 'Acme\ClassName',
                'message' => 'Unable to update widget when stationary',
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param array<mixed> $expectedData
     */
    public function testGetData(UnknownExceptionData $unknownExceptionData, array $expectedData): void
    {
        self::assertSame($expectedData, $unknownExceptionData->getData());
    }

    /**
     * @return array<mixed>
     */
    public static function getDataDataProvider(): array
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
                ],
            ],
        ];
    }
}
