<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Source;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\IdentifierFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\Model\Node;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;
use webignition\ObjectReflector\ObjectReflector;

class NodeSourceTest extends AbstractBaseTestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(Node $body): void
    {
        $node = new NodeSource($body);

        self::assertSame($body, ObjectReflector::getProperty($node, 'body'));
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
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
     * @param array<mixed> $expectedData
     */
    public function testGetData(NodeSource $source, array $expectedData): void
    {
        self::assertSame($expectedData, $source->getData());
    }

    /**
     * @return array<mixed>
     */
    public static function getDataDataProvider(): array
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
