<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\Exception;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\Exception\InvalidLocator;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class InvalidLocatorTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $type, string $locator, NodeSource $source)
    {
        $invalidLocator = new InvalidLocator($type, $locator, $source);

        self::assertSame($type, ObjectReflector::getProperty($invalidLocator, 'type'));
        self::assertSame($locator, ObjectReflector::getProperty($invalidLocator, 'locator'));
        self::assertSame($source, ObjectReflector::getProperty($invalidLocator, 'source'));
    }

    public function createDataProvider(): array
    {
        $nodeSourceFactory = NodeSourceFactory::createFactory();

        return [
            'default' => [
                'type' => 'css',
                'locator' => 'a[href=https://example.com]',
                'source' =>
                    $nodeSourceFactory->create('$"a[href=https://example.com]"') ?? \Mockery::mock(NodeSource::class),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param InvalidLocator $invalidLocator
     * @param array<mixed> $expectedData
     */
    public function testGetData(InvalidLocator $invalidLocator, array $expectedData)
    {
        self::assertSame($expectedData, $invalidLocator->getData());
    }

    public function getDataDataProvider(): array
    {
        $nodeSourceFactory = NodeSourceFactory::createFactory();
        $nodeSource = $nodeSourceFactory->create('$"a[href=https://example.com]"') ?? \Mockery::mock(NodeSource::class);

        return [
            'default' => [
                'invalidLocator' => new InvalidLocator('css', 'a[href=https://example.com]', $nodeSource),
                'expectedData' => [
                    'type' => 'invalid-locator',
                    'body' => [
                        'type' => 'css',
                        'locator' => 'a[href=https://example.com]',
                        'source' => $nodeSource->getData(),
                    ],
                ]
            ],
        ];
    }
}
