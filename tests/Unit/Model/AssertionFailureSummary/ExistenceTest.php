<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\AssertionFailureSummary;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;
use webignition\ObjectReflector\ObjectReflector;

class ExistenceTest extends AbstractBaseTestCase
{
    #[DataProvider('createDataProvider')]
    public function testCreate(string $operator, NodeSource $source): void
    {
        $summary = new Existence($operator, $source);

        self::assertSame($operator, ObjectReflector::getProperty($summary, 'operator'));
        self::assertSame($source, ObjectReflector::getProperty($summary, 'source'));
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        return [
            'default' => [
                'operator' => 'exists',
                'source' => \Mockery::mock(NodeSource::class),
            ],
        ];
    }

    /**
     * @param array<mixed> $expectedData
     */
    #[DataProvider('getDataDataProvider')]
    public function testGetData(Existence $summary, array $expectedData): void
    {
        self::assertSame($expectedData, $summary->getData());
    }

    /**
     * @return array<mixed>
     */
    public static function getDataDataProvider(): array
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
