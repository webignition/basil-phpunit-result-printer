<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class ExistenceTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $operator, NodeSource $source): void
    {
        $summary = new Existence($operator, $source);

        self::assertSame($operator, ObjectReflector::getProperty($summary, 'operator'));
        self::assertSame($source, ObjectReflector::getProperty($summary, 'source'));
    }

    /**
     * @return array<mixed>
     */
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
     * @param array<mixed> $expectedData
     */
    public function testGetData(Existence $summary, array $expectedData): void
    {
        self::assertSame($expectedData, $summary->getData());
    }

    /**
     * @return array<mixed>
     */
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
