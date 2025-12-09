<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\ExceptionData;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\InvalidLocatorExceptionData;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;
use webignition\ObjectReflector\ObjectReflector;

class InvalidLocatorExceptionDataTest extends AbstractBaseTestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $type, string $locator, NodeSource $source): void
    {
        $invalidLocatorExceptionData = new InvalidLocatorExceptionData($type, $locator, $source);

        self::assertSame($type, ObjectReflector::getProperty($invalidLocatorExceptionData, 'type'));
        self::assertSame($locator, ObjectReflector::getProperty($invalidLocatorExceptionData, 'locator'));
        self::assertSame($source, ObjectReflector::getProperty($invalidLocatorExceptionData, 'source'));
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
    {
        $nodeSourceFactory = NodeSourceFactory::createFactory();

        return [
            'default' => [
                'type' => 'css',
                'locator' => 'a[href=https://example.com]',
                'source' => $nodeSourceFactory->create('$"a[href=https://example.com]"')
                    ?? \Mockery::mock(NodeSource::class),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param array<mixed> $expectedData
     */
    public function testGetData(InvalidLocatorExceptionData $invalidLocatorExceptionData, array $expectedData): void
    {
        self::assertSame($expectedData, $invalidLocatorExceptionData->getData());
    }

    /**
     * @return array<mixed>
     */
    public function getDataDataProvider(): array
    {
        $nodeSourceFactory = NodeSourceFactory::createFactory();
        $nodeSource = $nodeSourceFactory->create('$"a[href=https://example.com]"') ?? \Mockery::mock(NodeSource::class);

        return [
            'default' => [
                'invalidLocatorExceptionData' => new InvalidLocatorExceptionData(
                    'css',
                    'a[href=https://example.com]',
                    $nodeSource
                ),
                'expectedData' => [
                    'type' => 'invalid-locator',
                    'body' => [
                        'type' => 'css',
                        'locator' => 'a[href=https://example.com]',
                        'source' => $nodeSource->getData(),
                    ],
                ],
            ],
        ];
    }
}
