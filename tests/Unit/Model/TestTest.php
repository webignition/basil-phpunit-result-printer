<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilModels\Test\Configuration;
use webignition\BasilModels\Test\ConfigurationInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Test;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class TestTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $path, ConfigurationInterface $configuration)
    {
        $test = new Test($path, $configuration);

        self::assertSame($path, ObjectReflector::getProperty($test, 'path'));
        self::assertSame($configuration, ObjectReflector::getProperty($test, 'configuration'));
    }

    public function createDataProvider(): array
    {
        return [
            'default' => [
                'path' => 'test.yml',
                'configuration' => new Configuration('chrome', 'http://example.com'),
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
                    'test.yml',
                    new Configuration('chrome', 'http://example.com')
                ),
                'expectedData' => [
                    'path' => 'test.yml',
                    'config' => [
                        'browser' => 'chrome',
                        'url' => 'http://example.com'
                    ],
                ],
            ],
        ];
    }
}
