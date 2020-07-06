<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel;

use webignition\BasilPhpUnitResultPrinter\FooModel\Test;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class TestTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $path)
    {
        $test = new Test($path);

        self::assertSame($path, ObjectReflector::getProperty($test, 'path'));
    }

    public function createDataProvider(): array
    {
        return [
            'default' => [
                'path' => 'test.yml',
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param Test $test
     * @param array<mixed> $expectedData
     */
    public function testGetData(Test $test, array $expectedData)
    {
        self::assertSame($expectedData, $test->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'default' => [
                'test' => new Test(
                    'test.yml'
                ),
                'expectedData' => [
                    'path' => 'test.yml',
                ],
            ],
        ];
    }
}
