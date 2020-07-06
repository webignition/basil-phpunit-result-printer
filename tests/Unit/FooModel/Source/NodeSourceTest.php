<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\Source;

use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class NodeSourceTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(Node $body)
    {
        $node = new NodeSource($body);

        self::assertSame($body, ObjectReflector::getProperty($node, 'body'));
    }

    public function createDataProvider(): array
    {
        return [
            'default' => [
                'body' => new Node(
                    Node::TYPE_ELEMENT,
                    new Identifier(
                        '$".selector"',
                        new Properties(Properties::TYPE_CSS, '.selector', 1),
                    )
                ),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param NodeSource $source
     * @param array<mixed> $expectedData
     */
    public function testGetData(NodeSource $source, array $expectedData)
    {
        self::assertSame($expectedData, $source->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'default' => [
                'source' => new NodeSource(
                    new Node(
                        Node::TYPE_ELEMENT,
                        new Identifier(
                            '$".selector"',
                            new Properties(Properties::TYPE_CSS, '.selector', 1),
                        )
                    )
                ),
                'expectedData' => [
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
        ];
    }
}
