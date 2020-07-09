<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\Source;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\IdentifierFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
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
                    \Mockery::mock(Identifier::class)
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
        $identifierFactory = IdentifierFactory::createFactory();

        $identifier = $identifierFactory->create('$".selector"') ?? \Mockery::mock(Identifier::class);
        $node = Node::fromIdentifier($identifier);

        return [
            'default' => [
                'source' => new NodeSource($node),
                'expectedData' => [
                    'type' => 'node',
                    'body' => $node->getData(),
                ],
            ],
        ];
    }
}
