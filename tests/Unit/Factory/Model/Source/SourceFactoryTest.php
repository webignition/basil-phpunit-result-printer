<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory\Source;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\SourceFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\FooModel\Scalar;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\SourceInterface;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class SourceFactoryTest extends AbstractBaseTest
{
    private SourceFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = SourceFactory::createFactory();
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $source, ?SourceInterface $expectedNodeSource)
    {
        self::assertEquals($expectedNodeSource, $this->factory->create($source));
    }

    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'source' => '',
                'expectedSource' => null,
            ],
            'node: element' => [
                'source' => '$".selector"',
                'expectedSource' => new NodeSource(
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
            'node: attribute' => [
                'source' => '$".selector".attribute_name',
                'expectedSource' => new NodeSource(
                    Node::fromIdentifier(
                        new Identifier(
                            '$".selector".attribute_name',
                            (new Properties(
                                Properties::TYPE_CSS,
                                '.selector',
                                1
                            ))->withAttribute('attribute_name')
                        )
                    )
                ),
            ],
            'node: descendant' => [
                'source' => '$".parent" >> $".child"',
                'expectedSource' => new NodeSource(
                    Node::fromIdentifier(
                        new Identifier(
                            '$".parent" >> $".child"',
                            (new Properties(
                                Properties::TYPE_CSS,
                                '.child',
                                1
                            ))->withParent(new Properties(
                                Properties::TYPE_CSS,
                                '.parent',
                                1
                            ))
                        )
                    )
                ),
            ],
            'scalar: browser property' => [
                'source' => '$browser.size',
                'expectedSource' => new ScalarSource(new Scalar(
                    Scalar::TYPE_BROWSER_PROPERTY,
                    '$browser.size'
                )),
            ],
            'scalar: data parameter' => [
                'source' => '$data.key',
                'expectedSource' => new ScalarSource(new Scalar(
                    Scalar::TYPE_DATA_PARAMETER,
                    '$data.key'
                )),
            ],
            'scalar: environment parameter' => [
                'source' => '$env.key',
                'expectedSource' => new ScalarSource(new Scalar(
                    Scalar::TYPE_ENVIRONMENT_PARAMETER,
                    '$env.key'
                )),
            ],
            'scalar: literal' => [
                'source' => '"literal"',
                'expectedSource' => new ScalarSource(new Scalar(
                    Scalar::TYPE_LITERAL,
                    '"literal"'
                )),
            ],
            'scalar: page property' => [
                'source' => '$page.url',
                'expectedSource' => new ScalarSource(new Scalar(
                    Scalar::TYPE_PAGE_PROPERTY,
                    '$page.url'
                )),
            ],
        ];
    }
}
