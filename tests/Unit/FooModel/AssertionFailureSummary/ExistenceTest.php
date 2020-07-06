<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class ExistenceTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $operator, NodeSource $source)
    {
        $summary = new Existence($operator, $source);

        self::assertSame($operator, ObjectReflector::getProperty($summary, 'operator'));
        self::assertSame($source, ObjectReflector::getProperty($summary, 'source'));
    }

    public function createDataProvider(): array
    {
        return [
            'default' => [
                'operator' => 'exists',
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
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param Existence $summary
     * @param array<mixed> $expectedData
     */
    public function testGetData(Existence $summary, array $expectedData)
    {
        self::assertSame($expectedData, $summary->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'default' => [
                'summary' => new Existence(
                    'exists',
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
                    'operator' => 'exists',
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
        ];
    }
}
