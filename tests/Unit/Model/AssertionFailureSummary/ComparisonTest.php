<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\ValueFactory;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\Comparison;
use webignition\BasilPhpUnitResultPrinter\Model\Value;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class ComparisonTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $operator, Value $expected, Value $actual)
    {
        $summary = new Comparison($operator, $expected, $actual);

        self::assertSame($operator, ObjectReflector::getProperty($summary, 'operator'));
        self::assertSame($expected, ObjectReflector::getProperty($summary, 'expected'));
        self::assertSame($actual, ObjectReflector::getProperty($summary, 'actual'));
    }

    public function createDataProvider(): array
    {
        return [
            'default' => [
                'operator' => 'is',
                'expected' => \Mockery::mock(Value::class),
                'actual' => \Mockery::mock(Value::class),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param Comparison $summary
     * @param array<mixed> $expectedData
     */
    public function testGetData(Comparison $summary, array $expectedData)
    {
        self::assertSame($expectedData, $summary->getData());
    }

    public function getDataDataProvider(): array
    {
        $mockValue = \Mockery::mock(Value::class);

        $valueFactory = ValueFactory::createFactory();
        $expectedValue = $valueFactory->create('expected value', '"expected value"') ?? $mockValue;
        $actualValue = $valueFactory->create('actual value', '$".selector"') ?? $mockValue;

        return [
            'default' => [
                'summary' => new Comparison('is', $expectedValue, $actualValue),
                'expectedData' => [
                    'operator' => 'is',
                    'expected' => $expectedValue->getData(),
                    'actual' => $actualValue->getData(),
                ],
            ],
        ];
    }
}
