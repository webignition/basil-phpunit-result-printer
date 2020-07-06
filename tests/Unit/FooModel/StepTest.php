<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel;

use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\ActionStatement;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\FailedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\PassedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\FooModel\Step;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class StepTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     *
     * @param string $name
     * @param string $status
     * @param StatementInterface[] $statements
     */
    public function testCreate(string $name, string $status, array $statements)
    {
        $step = new Step($name, $status, $statements);

        self::assertSame($name, ObjectReflector::getProperty($step, 'name'));
        self::assertSame($status, ObjectReflector::getProperty($step, 'status'));
        self::assertSame($statements, ObjectReflector::getProperty($step, 'statements'));
    }

    public function createDataProvider(): array
    {
        return [
            'passed, single assertion' => [
                'name' => 'passed, single assertion',
                'status' => Step::STATUS_PASSED,
                'statements' => [
                    new PassedAssertionStatement(
                        '$".selector" exists'
                    ),
                ],
            ],
            'passed, single action, single assertion' => [
                'name' => 'passed, single action, single assertion',
                'status' => Step::STATUS_PASSED,
                'statements' => [
                    new ActionStatement(
                        'click $".button"',
                        'passed'
                    ),
                    new PassedAssertionStatement(
                        '$".selector" exists'
                    ),
                ],
            ],
            'failed, single assertion' => [
                'name' => 'failed, single assertion',
                'status' => Step::STATUS_FAILED,
                'statements' => [
                    new FailedAssertionStatement(
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
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param Step $step
     * @param array<mixed> $expectedData
     */
    public function testGetData(Step $step, array $expectedData)
    {
        self::assertSame($expectedData, $step->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'passed, single assertion' => [
                'step' => new Step(
                    'passed, single assertion',
                    Step::STATUS_PASSED,
                    [
                        new PassedAssertionStatement(
                            '$".selector" exists'
                        ),
                    ]
                ),
                'expectedData' => [
                    'name' => 'passed, single assertion',
                    'status' => 'passed',
                    'statements' => [
                        [
                            'type' => 'assertion',
                            'source' => '$".selector" exists',
                            'status' => 'passed',
                        ],
                    ],
                ],
            ],
            'passed, single action, single assertion' => [
                'step' => new Step(
                    'passed, single action, single assertion',
                    Step::STATUS_PASSED,
                    [
                        new ActionStatement(
                            'click $".button"',
                            'passed'
                        ),
                        new PassedAssertionStatement(
                            '$".selector" exists'
                        ),
                    ]
                ),
                'expectedData' => [
                    'name' => 'passed, single action, single assertion',
                    'status' => 'passed',
                    'statements' => [
                        [
                            'type' => 'action',
                            'source' => 'click $".button"',
                            'status' => 'passed',
                        ],
                        [
                            'type' => 'assertion',
                            'source' => '$".selector" exists',
                            'status' => 'passed',
                        ],
                    ],
                ],
            ],
            'failed, single assertion' => [
                'step' => new Step(
                    'failed, single assertion',
                    Step::STATUS_FAILED,
                    [
                        new FailedAssertionStatement(
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
                    ]
                ),
                'expectedData' => [
                    'name' => 'failed, single assertion',
                    'status' => 'failed',
                    'statements' => [
                        [
                            'type' => 'assertion',
                            'source' => '$".selector" exists',
                            'status' => 'failed',
                            'summary' => [
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
                    ],
                ],
                'statements' => [
                    new FailedAssertionStatement(
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
            ],
        ];
    }
}
