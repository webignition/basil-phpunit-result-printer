<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\Comparison;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\FooModel\Scalar;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Value;
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
                'expected' => new Value(
                    'expected value',
                    new ScalarSource(
                        new Scalar(
                            Scalar::TYPE_LITERAL,
                            'expected value'
                        )
                    )
                ),
                'actual' => new Value(
                    'actual value',
                    new NodeSource(
                        new Node(
                            Node::TYPE_ELEMENT,
                            new Identifier(
                                '$".selector"',
                                new Properties(Properties::TYPE_CSS, '.selector', 1),
                            )
                        )
                    )
                ),
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
        return [
            'default' => [
                'summary' => new Comparison(
                    'is',
                    new Value(
                        'expected value',
                        new ScalarSource(
                            new Scalar(
                                Scalar::TYPE_LITERAL,
                                'expected value'
                            )
                        )
                    ),
                    new Value(
                        'actual value',
                        new NodeSource(
                            new Node(
                                Node::TYPE_ELEMENT,
                                new Identifier(
                                    '$".selector"',
                                    new Properties(Properties::TYPE_CSS, '.selector', 1),
                                )
                            )
                        )
                    )
                ),
                'expectedData' => [
                    'operator' => 'is',
                    'expected' => [
                        'value' => 'expected value',
                        'source' => [
                            'type' => 'scalar',
                            'body' => [
                                'type' => Scalar::TYPE_LITERAL,
                                'value' => 'expected value',
                            ],
                        ],
                    ],
                    'actual' => [
                        'value' => 'actual value',
                        'source' => [
                            'type' => 'node',
                            'body' => [
                                'type' => Node::TYPE_ELEMENT,
                                'identifier' => [
                                    'source' => '$".selector"',
                                    'properties' => [
                                        'type' => Properties::TYPE_CSS,
                                        'locator' => '.selector',
                                        'position' => 1,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
