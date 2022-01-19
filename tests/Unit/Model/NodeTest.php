<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\IdentifierFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\Model\Node;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class NodeTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $type, Identifier $identifier): void
    {
        $node = new Node($type, $identifier);

        self::assertSame($type, ObjectReflector::getProperty($node, 'type'));
        self::assertSame($identifier, ObjectReflector::getProperty($node, 'identifier'));
    }

    /**
     * @return array[]
     */
    public function createDataProvider(): array
    {
        return [
            'default' => [
                'type' => Node::TYPE_ELEMENT,
                'identifier' => \Mockery::mock(Identifier::class),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param array<mixed> $expectedData
     */
    public function testGetData(Node $node, array $expectedData): void
    {
        self::assertSame($expectedData, $node->getData());
    }

    /**
     * @return array[]
     */
    public function getDataDataProvider(): array
    {
        $identifierFactory = IdentifierFactory::createFactory();

        $elementIdentifier = $identifierFactory->create('$".selector"') ?? \Mockery::mock(Identifier::class);
        $attributeIdentifier = $identifierFactory->create(
            '$".selector".attribute_name'
        ) ?? \Mockery::mock(Identifier::class);

        return [
            'element' => [
                'node' => new Node(Node::TYPE_ELEMENT, $elementIdentifier),
                'expectedData' => [
                    'type' => Node::TYPE_ELEMENT,
                    'identifier' => $elementIdentifier->getData(),
                ],
            ],
            'attribute' => [
                'node' => new Node(
                    Node::TYPE_ATTRIBUTE,
                    $attributeIdentifier
                ),
                'expectedData' => [
                    'type' => Node::TYPE_ATTRIBUTE,
                    'identifier' => $attributeIdentifier->getData(),
                ],
            ],
        ];
    }

    /**
     * @dataProvider fromIdentifierDataProvider
     */
    public function testFromIdentifier(Identifier $identifier, Node $expectedNode): void
    {
        self::assertEquals($expectedNode, Node::fromIdentifier($identifier));
    }

    /**
     * @return array[]
     */
    public function fromIdentifierDataProvider(): array
    {
        $elementProperties = new Properties(
            Properties::TYPE_CSS,
            '.selector',
            1
        );

        $elementIdentifier = new Identifier('$".selector"', $elementProperties);

        $attributeIdentifier = new Identifier(
            '$".selector".attribute_name',
            $elementProperties->withAttribute('attribute_name')
        );

        return [
            'element' => [
                'identifier' => $elementIdentifier,
                'expectedNode' => new Node(
                    Node::TYPE_ELEMENT,
                    $elementIdentifier
                ),
            ],
            'attribute' => [
                'identifier' => $attributeIdentifier,
                'expectedNode' => new Node(
                    Node::TYPE_ATTRIBUTE,
                    $attributeIdentifier
                ),
            ],
        ];
    }
}
