<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class ExistenceTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $operator, NodeSource $source)
    {
        $summary = new Existence($operator, $source);

        self::assertSame($operator, ObjectReflector::getProperty($summary, 'operator'));
        self::assertSame($source, ObjectReflector::getProperty($summary, 'source'));
    }

    public function createDataProvider(): array
    {
        return [
            'default' => [
                'operator' => 'exists',
                'source' => \Mockery::mock(NodeSource::class),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param Existence $summary
     * @param array<mixed> $expectedData
     */
    public function testGetData(Existence $summary, array $expectedData)
    {
        self::assertSame($expectedData, $summary->getData());
    }

    public function getDataDataProvider(): array
    {
        $nodeSourceFactory = NodeSourceFactory::createFactory();
        $nodeSource = $nodeSourceFactory->create('$".selector"') ?? \Mockery::mock(NodeSource::class);

        return [
            'default' => [
                'summary' => new Existence('exists', $nodeSource),
                'expectedData' => [
                    'operator' => 'exists',
                    'source' => $nodeSource->getData(),
                ],
            ],
        ];
    }
}
