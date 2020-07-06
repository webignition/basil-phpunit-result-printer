<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\Statement;

use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\FailedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\Transformation;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class FailedAssertionStatementTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     *
     * @param string $source
     * @param AssertionFailureSummaryInterface $summary
     * @param array<mixed> $transformations
     * @param FailedAssertionStatement $expectedStatement
     */
    public function testCreate(
        string $source,
        AssertionFailureSummaryInterface $summary,
        array $transformations,
        FailedAssertionStatement $expectedStatement
    ) {
        $statement = new FailedAssertionStatement($source, $summary, $transformations);

        self::assertEquals($expectedStatement, $statement);
    }

    public function createDataProvider(): array
    {
        return [
            'no transformations' => [
                'source' => '$".selector" exists',
                'summary' => new Existence(
                    'exists',
                    new NodeSource(
                        new Node(
                            Node::TYPE_ELEMENT,
                            new Identifier(
                                '$".selector"',
                                new Properties(
                                    Properties::TYPE_CSS,
                                    '.selector',
                                    1
                                )
                            )
                        )
                    )
                ),
                'transformations' => [],
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    new Existence(
                        'exists',
                        new NodeSource(
                            new Node(
                                Node::TYPE_ELEMENT,
                                new Identifier(
                                    '$".selector"',
                                    new Properties(
                                        Properties::TYPE_CSS,
                                        '.selector',
                                        1
                                    )
                                )
                            )
                        )
                    )
                ),
            ],
            'invalid transformations' => [
                'source' => '$".selector" exists',
                'summary' => new Existence(
                    'exists',
                    new NodeSource(
                        new Node(
                            Node::TYPE_ELEMENT,
                            new Identifier(
                                '$".selector"',
                                new Properties(
                                    Properties::TYPE_CSS,
                                    '.selector',
                                    1
                                )
                            )
                        )
                    )
                ),
                'transformations' => [
                    new \stdClass(),
                ],
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    new Existence(
                        'exists',
                        new NodeSource(
                            new Node(
                                Node::TYPE_ELEMENT,
                                new Identifier(
                                    '$".selector"',
                                    new Properties(
                                        Properties::TYPE_CSS,
                                        '.selector',
                                        1
                                    )
                                )
                            )
                        )
                    )
                ),
            ],
            'valid transformations' => [
                'source' => '$".selector" exists',
                'summary' => new Existence(
                    'exists',
                    new NodeSource(
                        new Node(
                            Node::TYPE_ELEMENT,
                            new Identifier(
                                '$".selector"',
                                new Properties(
                                    Properties::TYPE_CSS,
                                    '.selector',
                                    1
                                )
                            )
                        )
                    )
                ),
                'transformations' => [
                    new Transformation(
                        Transformation::TYPE_DERIVATION,
                        'click $".selector"'
                    ),
                    new Transformation(
                        Transformation::TYPE_RESOLUTION,
                        'click $page_import_name.elements.element_name'
                    ),
                ],
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    new Existence(
                        'exists',
                        new NodeSource(
                            new Node(
                                Node::TYPE_ELEMENT,
                                new Identifier(
                                    '$".selector"',
                                    new Properties(
                                        Properties::TYPE_CSS,
                                        '.selector',
                                        1
                                    )
                                )
                            )
                        )
                    ),
                    [
                        new Transformation(
                            Transformation::TYPE_DERIVATION,
                            'click $".selector"'
                        ),
                        new Transformation(
                            Transformation::TYPE_RESOLUTION,
                            'click $page_import_name.elements.element_name'
                        ),
                    ]
                ),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param FailedAssertionStatement $statement
     * @param array<mixed> $expectedData
     */
    public function testGetData(FailedAssertionStatement $statement, array $expectedData)
    {
        self::assertSame($expectedData, $statement->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'no transformations' => [
                'statement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    new Existence(
                        'exists',
                        new NodeSource(
                            new Node(
                                Node::TYPE_ELEMENT,
                                new Identifier(
                                    '$".selector"',
                                    new Properties(
                                        Properties::TYPE_CSS,
                                        '.selector',
                                        1
                                    )
                                )
                            )
                        )
                    )
                ),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$".selector" exists',
                    'status' => 'failed',
                    'summary' => [
                        'operator' => 'exists',
                        'source' => [
                            'type' => 'node',
                            'body' => [
                                'type' => 'element',
                                'identifier' => [
                                    'source' => '$".selector"',
                                    'properties' => [
                                        'type' => 'css',
                                        'locator' => '.selector',
                                        'position' => 1,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'invalid transformations' => [
                'statement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    new Existence(
                        'exists',
                        new NodeSource(
                            new Node(
                                Node::TYPE_ELEMENT,
                                new Identifier(
                                    '$".selector"',
                                    new Properties(
                                        Properties::TYPE_CSS,
                                        '.selector',
                                        1
                                    )
                                )
                            )
                        )
                    )
                ),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$".selector" exists',
                    'status' => 'failed',
                    'summary' => [
                        'operator' => 'exists',
                        'source' => [
                            'type' => 'node',
                            'body' => [
                                'type' => 'element',
                                'identifier' => [
                                    'source' => '$".selector"',
                                    'properties' => [
                                        'type' => 'css',
                                        'locator' => '.selector',
                                        'position' => 1,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'valid transformations' => [
                'statement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    new Existence(
                        'exists',
                        new NodeSource(
                            new Node(
                                Node::TYPE_ELEMENT,
                                new Identifier(
                                    '$".selector"',
                                    new Properties(
                                        Properties::TYPE_CSS,
                                        '.selector',
                                        1
                                    )
                                )
                            )
                        )
                    ),
                    [
                        new Transformation(
                            Transformation::TYPE_DERIVATION,
                            'click $".selector"'
                        ),
                        new Transformation(
                            Transformation::TYPE_RESOLUTION,
                            'click $page_import_name.elements.element_name'
                        ),
                    ]
                ),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$".selector" exists',
                    'status' => 'failed',
                    'transformations' => [
                        [
                            'type' => Transformation::TYPE_DERIVATION,
                            'source' => 'click $".selector"',
                        ],
                        [
                            'type' => Transformation::TYPE_RESOLUTION,
                            'source' => 'click $page_import_name.elements.element_name',
                        ],
                    ],
                    'summary' => [
                        'operator' => 'exists',
                        'source' => [
                            'type' => 'node',
                            'body' => [
                                'type' => 'element',
                                'identifier' => [
                                    'source' => '$".selector"',
                                    'properties' => [
                                        'type' => 'css',
                                        'locator' => '.selector',
                                        'position' => 1,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
