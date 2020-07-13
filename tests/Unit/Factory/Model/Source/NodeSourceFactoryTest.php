<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory\Source;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\IdentifierFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\Model\Node;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class NodeSourceFactoryTest extends AbstractBaseTest
{
    private NodeSourceFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = NodeSourceFactory::createFactory();
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $source, ?NodeSource $expectedNodeSource)
    {
        self::assertEquals($expectedNodeSource, $this->factory->create($source));
    }

    public function createDataProvider(): array
    {
        $identifierFactory = IdentifierFactory::createFactory();

        return [
            'empty' => [
                'source' => '',
                'expectedNodeSource' => null,
            ],
            'non-empty' => [
                'source' => '$".selector"',
                'expectedNodeSource' => new NodeSource(
                    Node::fromIdentifier(
                        $identifierFactory->create('$".selector"') ?? \Mockery::mock(Identifier::class)
                    )
                ),
            ],
        ];
    }
}
