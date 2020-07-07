<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory\Source;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
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
        return [
            'empty' => [
                'source' => '',
                'expectedNodeSource' => null,
            ],
            'non-empty' => [
                'source' => '$".selector"',
                'expectedNodeSource' => new NodeSource(
                    Node::fromIdentifier(
                        new Identifier(
                            '$".selector"',
                            new Properties(
                                Properties::TYPE_CSS,
                                '.selector',
                                1
                            )
                        )
                    )
                ),
            ],
        ];
    }
}
