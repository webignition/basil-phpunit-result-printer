<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\Exception;

use webignition\BasilPhpUnitResultPrinter\FooModel\Exception\Unknown;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class UnknownTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $class, string $message)
    {
        $unknown = new Unknown($class, $message);

        self::assertSame($class, ObjectReflector::getProperty($unknown, 'class'));
        self::assertSame($message, ObjectReflector::getProperty($unknown, 'message'));
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
     * @param Unknown $unknown
     * @param array<mixed> $expectedData
     */
    public function testGetData(Unknown $unknown, array $expectedData)
    {
        self::assertSame($expectedData, $unknown->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'default' => [
                'unknown' => new Unknown('Acme\ClassName', 'Unable to update widget when stationary'),
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
