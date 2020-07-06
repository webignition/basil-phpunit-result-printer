<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel;

use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class NodeTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $type, Identifier $identifier)
    {
        self::assertTrue(true);

        $node = new Node($type, $identifier);

        self::assertSame($type, ObjectReflector::getProperty($node, 'type'));
        self::assertSame($identifier, ObjectReflector::getProperty($node, 'identifier'));
    }

    public function createDataProvider(): array
    {
        return [
            'default' => [
                'type' => Node::TYPE_ELEMENT,
                'identifier' => new Identifier(
                    '$".selector',
                    new Properties(
                        Properties::TYPE_CSS,
                        '.selector',
                        1
                    )
                ),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param Node $node
     * @param array<mixed> $expectedData
     */
    public function testGetData(Node $node, array $expectedData)
    {
        self::assertSame($expectedData, $node->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'element' => [
                'node' => new Node(
                    Node::TYPE_ELEMENT,
                    new Identifier(
                        '$".selector"',
                        new Properties(Properties::TYPE_CSS, '.selector', 1),
                    )
                ),
                'expectedData' => [
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
            'attribute' => [
                'node' => new Node(
                    Node:: TYPE_ATTRIBUTE,
                    new Identifier(
                        '$".selector".attribute_name',
                        (new Properties(Properties::TYPE_CSS, '.selector', 1))
                            ->withAttribute('attribute_name'),
                    )
                ),
                'expectedData' => [
                    'type' => Node::TYPE_ATTRIBUTE,
                    'identifier' => [
                        'source' => '$".selector".attribute_name',
                        'properties' => [
                            'type' => Properties::TYPE_CSS,
                            'locator' => '.selector',
                            'position' => 1,
                            'attribute' => 'attribute_name',
                        ],
                    ],
                ],
            ],
        ];
    }
}
