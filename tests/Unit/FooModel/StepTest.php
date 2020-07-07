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
use webignition\BasilPhpUnitResultPrinter\FooModel\Status;
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
        $statusPassed = (string) new Status(Status::STATUS_PASSED);
        $statusFailed = (string) new Status(Status::STATUS_FAILED);

        return [
            'passed, single assertion' => [
                'name' => 'passed, single assertion',
                'status' => $statusPassed,
                'statements' => [
                    new PassedAssertionStatement(
                        '$".selector" exists'
                    ),
                ],
            ],
            'passed, single action, single assertion' => [
                'name' => 'passed, single action, single assertion',
                'status' => $statusPassed,
                'statements' => [
                    new ActionStatement(
                        'click $".button"',
                        $statusPassed
                    ),
                    new PassedAssertionStatement(
                        '$".selector" exists'
                    ),
                ],
            ],
            'failed, single assertion' => [
                'name' => 'failed, single assertion',
                'status' => $statusFailed,
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
        $statusPassed = (string) new Status(Status::STATUS_PASSED);
        $statusFailed = (string) new Status(Status::STATUS_FAILED);

        return [
            'passed, single assertion' => [
                'step' => new Step(
                    'passed, single assertion',
                    $statusPassed,
                    [
                        new PassedAssertionStatement(
                            '$".selector" exists'
                        ),
                    ]
                ),
                'expectedData' => [
                    'name' => 'passed, single assertion',
                    'status' => $statusPassed,
                    'statements' => [
                        [
                            'type' => 'assertion',
                            'source' => '$".selector" exists',
                            'status' => $statusPassed,
                        ],
                    ],
                ],
            ],
            'passed, single action, single assertion' => [
                'step' => new Step(
                    'passed, single action, single assertion',
                    $statusPassed,
                    [
                        new ActionStatement(
                            'click $".button"',
                            $statusPassed
                        ),
                        new PassedAssertionStatement(
                            '$".selector" exists'
                        ),
                    ]
                ),
                'expectedData' => [
                    'name' => 'passed, single action, single assertion',
                    'status' => $statusPassed,
                    'statements' => [
                        [
                            'type' => 'action',
                            'source' => 'click $".button"',
                            'status' => $statusPassed,
                        ],
                        [
                            'type' => 'assertion',
                            'source' => '$".selector" exists',
                            'status' => $statusPassed,
                        ],
                    ],
                ],
            ],
            'failed, single assertion' => [
                'step' => new Step(
                    'failed, single assertion',
                    $statusFailed,
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
                    'status' => $statusFailed,
                    'statements' => [
                        [
                            'type' => 'assertion',
                            'source' => '$".selector" exists',
                            'status' => $statusFailed,
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
