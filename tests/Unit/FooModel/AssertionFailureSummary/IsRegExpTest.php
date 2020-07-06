<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\IsRegExp;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\FooModel\Scalar;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\SourceInterface;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class IsRegExpTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $value, SourceInterface $source)
    {
        $summary = new IsRegExp($value, $source);

        self::assertSame($value, ObjectReflector::getProperty($summary, 'value'));
        self::assertSame($source, ObjectReflector::getProperty($summary, 'source'));
    }

    public function createDataProvider(): array
    {
        return [
            'node' => [
                'value' => 'not a regexp from node',
                'source' => new NodeSource(
                    new Node(
                        Node::TYPE_ELEMENT,
                        new Identifier(
                            '$".selector"',
                            new Properties(Properties::TYPE_CSS, '.selector', 1),
                        )
                    )
                ),
            ],
            'scalar' => [
                'value' => 'not a regexp from scalar',
                'source' => new ScalarSource(
                    new Scalar(
                        Scalar::TYPE_LITERAL,
                        'literal'
                    )
                ),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param IsRegExp $summary
     * @param array<mixed> $expectedData
     */
    public function testGetData(IsRegExp $summary, array $expectedData)
    {
        self::assertSame($expectedData, $summary->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'node' => [
                'summary' => new IsRegExp(
                    'not a regexp from node',
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
                'expectedData' => [
                    'operator' => 'is-regexp',
                    'value' => 'not a regexp from node',
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
            'scalar' => [
                'summary' => new IsRegExp(
                    'not a regexp from scalar',
                    new ScalarSource(
                        new Scalar(
                            Scalar::TYPE_LITERAL,
                            'not a regexp from scalar'
                        )
                    )
                ),
                'expectedData' => [
                    'operator' => 'is-regexp',
                    'value' => 'not a regexp from scalar',
                    'source' => [
                        'type' => 'scalar',
                        'body' => [
                            'type' => Scalar::TYPE_LITERAL,
                            'value' => 'not a regexp from scalar',
                        ],
                    ],
                ],
            ],
        ];
    }
}
