<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\SourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Model\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\Model\Source\SourceInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Value;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class ValueTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $value, SourceInterface $source): void
    {
        $node = new Value($value, $source);

        self::assertSame($value, ObjectReflector::getProperty($node, 'value'));
        self::assertSame($source, ObjectReflector::getProperty($node, 'source'));
    }

    /**
     * @return array[]
     */
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
     * @param array<mixed> $expectedData
     */
    public function testGetData(Value $value, array $expectedData): void
    {
        self::assertSame($expectedData, $value->getData());
    }

    /**
     * @return array[]
     */
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
