<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel;

use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\FooModel\Scalar;
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
                'value' => 'actual',
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
     * @param Value $value
     * @param array<mixed> $expectedData
     */
    public function testGetData(Value $value, array $expectedData)
    {
        self::assertSame($expectedData, $value->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'node' => [
                'value' => new Value(
                    'expected',
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
                    'value' => 'expected',
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
                'value' => new Value(
                    'actual',
                    new ScalarSource(
                        new Scalar(
                            Scalar::TYPE_LITERAL,
                            'literal'
                        )
                    )
                ),
                'expectedData' => [
                    'value' => 'actual',
                    'source' => [
                        'type' => 'scalar',
                        'body' => [
                            'type' => Scalar::TYPE_LITERAL,
                            'value' => 'literal',
                        ],
                    ],
                ],
            ],
        ];
    }
}
