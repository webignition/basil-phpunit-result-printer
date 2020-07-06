<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel;

use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\FooModel\Scalar;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source;
use webignition\BasilPhpUnitResultPrinter\FooModel\SourceBodyInterface;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class SourceTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $type, SourceBodyInterface $body)
    {
        $node = new Source($type, $body);

        self::assertSame($type, ObjectReflector::getProperty($node, 'type'));
        self::assertSame($body, ObjectReflector::getProperty($node, 'body'));
    }

    public function createDataProvider(): array
    {
        return [
            'node' => [
                'type' => Source::TYPE_NODE,
                'body' => new Node(
                    Node::TYPE_ELEMENT,
                    new Identifier(
                        '$".selector"',
                        new Properties(Properties::TYPE_CSS, '.selector', 1),
                    )
                ),
            ],
            'scalar' => [
                'type' => Source::TYPE_SCALAR,
                'body' => new Scalar(
                    Scalar::TYPE_LITERAL,
                    'literal'
                ),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param Source $source
     * @param array<mixed> $expectedData
     */
    public function testGetData(Source $source, array $expectedData)
    {
        self::assertSame($expectedData, $source->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'node' => [
                'source' => new Source(
                    Source::TYPE_NODE,
                    new Node(
                        Node::TYPE_ELEMENT,
                        new Identifier(
                            '$".selector"',
                            new Properties(Properties::TYPE_CSS, '.selector', 1),
                        )
                    )
                ),
                'expectedData' => [
                    'type' => Source::TYPE_NODE,
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
            'scalar' => [
                'source' => new Source(
                    Source::TYPE_SCALAR,
                    new Scalar(
                        Scalar::TYPE_LITERAL,
                        'literal'
                    )
                ),
                'expectedData' => [
                    'type' => Source::TYPE_SCALAR,
                    'body' => [
                        'type' => Scalar::TYPE_LITERAL,
                        'value' => 'literal',
                    ],
                ],
            ],
        ];
    }
}
