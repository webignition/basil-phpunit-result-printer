<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\SourceFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\SourceInterface;
use webignition\BasilPhpUnitResultPrinter\FooModel\Value;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class ValueTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $value, SourceInterface $source)
    {
        $node = new Value($value, $source);

        self::assertSame($value, ObjectReflector::getProperty($node, 'value'));
        self::assertSame($source, ObjectReflector::getProperty($node, 'source'));
    }

    public function createDataProvider(): array
    {
        return [
            'node' => [
                'value' => 'expected',
                'source' => \Mockery::mock(NodeSource::class),
            ],
            'scalar' => [
                'value' => 'actual',
                'source' => \Mockery::mock(ScalarSource::class),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param Value $value
     * @param array<mixed> $expectedData
     */
    public function testGetData(Value $value, array $expectedData)
    {
        self::assertSame($expectedData, $value->getData());
    }

    public function getDataDataProvider(): array
    {
        $sourceFactory = SourceFactory::createFactory();

        $nodeSource = $sourceFactory->create('$".selector"') ?? \Mockery::mock(NodeSource::class);
        $scalarSource = $sourceFactory->create('"literal"') ?? \Mockery::mock(ScalarSource::class);

        return [
            'node' => [
                'value' => new Value('expected', $nodeSource),
                'expectedData' => [
                    'value' => 'expected',
                    'source' => $nodeSource->getData(),
                ],
            ],
            'scalar' => [
                'value' => new Value('actual', $scalarSource),
                'expectedData' => [
                    'value' => 'actual',
                    'source' => $scalarSource->getData(),
                ],
            ],
        ];
    }
}
