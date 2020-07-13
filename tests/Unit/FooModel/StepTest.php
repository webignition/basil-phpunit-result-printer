<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel;

use webignition\BasilParser\ActionParser;
use webignition\BasilParser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement\StatementFactory;
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
     * @param array<mixed>|null $data
     */
    public function testCreate(string $name, string $status, array $statements, ?array $data)
    {
        $step = new Step($name, $status, $statements, $data);

        self::assertSame($name, ObjectReflector::getProperty($step, 'name'));
        self::assertSame($status, ObjectReflector::getProperty($step, 'status'));
        self::assertSame($statements, ObjectReflector::getProperty($step, 'statements'));
        self::assertSame($data, ObjectReflector::getProperty($step, 'data'));
    }

    public function createDataProvider(): array
    {
        $statusPassed = (string) new Status(Status::STATUS_PASSED);
        $statusFailed = (string) new Status(Status::STATUS_FAILED);

        return [
            'passed' => [
                'name' => 'passed, single assertion',
                'status' => $statusPassed,
                'statements' => [
                    \Mockery::mock(PassedAssertionStatement::class),
                ],
                'data' => null,
            ],
            'failed' => [
                'name' => 'failed, single assertion',
                'status' => $statusFailed,
                'statements' => [
                    \Mockery::mock(FailedAssertionStatement::class),
                ],
                'data' => null,
            ],
            'passed with data' => [
                'name' => 'passed, single assertion',
                'status' => $statusPassed,
                'statements' => [
                    \Mockery::mock(PassedAssertionStatement::class),
                ],
                'data' => [
                    'expected_value' => 'literal',
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
        $actionParser = ActionParser::create();
        $assertionParser = AssertionParser::create();
        $statementFactory = StatementFactory::createFactory();

        $clickAction = $actionParser->parse('click $".selector"');
        $existsAssertion = $assertionParser->parse('$".selector" exists');
        $isAssertionWithData = $assertionParser->parse('$".selector" is $data.expected_value');

        $passedActionStatement = $statementFactory->createForPassedAction(
            $clickAction
        ) ?? \Mockery::mock(ActionStatement::class);

        $passedExistsAssertionStatement = $statementFactory->createForPassedAssertion(
            $existsAssertion
        ) ?? \Mockery::mock(PassedAssertionStatement::class);

        $failedAssertionStatement = $statementFactory->createForFailedAssertion(
            $existsAssertion,
            '',
            ''
        ) ?? \Mockery::mock(FailedAssertionStatement::class);

        $passedIsAssertionWithDataStatement = $statementFactory->createForPassedAssertion(
            $isAssertionWithData
        ) ?? \Mockery::mock(PassedAssertionStatement::class);

        $statusPassed = (string) new Status(Status::STATUS_PASSED);
        $statusFailed = (string) new Status(Status::STATUS_FAILED);

        return [
            'passed, single assertion' => [
                'step' => new Step(
                    'passed, single assertion',
                    $statusPassed,
                    [
                        $passedExistsAssertionStatement,
                    ]
                ),
                'expectedData' => [
                    'name' => 'passed, single assertion',
                    'status' => $statusPassed,
                    'statements' => [
                        $passedExistsAssertionStatement->getData(),
                    ],
                ],
            ],
            'passed, single action, single assertion' => [
                'step' => new Step(
                    'passed, single action, single assertion',
                    $statusPassed,
                    [
                        $passedActionStatement,
                        $passedExistsAssertionStatement,
                    ]
                ),
                'expectedData' => [
                    'name' => 'passed, single action, single assertion',
                    'status' => $statusPassed,
                    'statements' => [
                        $passedActionStatement->getData(),
                        $passedExistsAssertionStatement->getData(),
                    ],
                ],
            ],
            'failed, single assertion' => [
                'step' => new Step(
                    'failed, single assertion',
                    $statusFailed,
                    [
                        $failedAssertionStatement,
                    ]
                ),
                'expectedData' => [
                    'name' => 'failed, single assertion',
                    'status' => $statusFailed,
                    'statements' => [
                        $failedAssertionStatement->getData(),
                    ],
                ],
            ],
            'passed, single is assertion with data' => [
                'step' => new Step(
                    'passed, single is assertion with data',
                    $statusPassed,
                    [
                        $passedIsAssertionWithDataStatement,
                    ],
                    [
                        'expected_value' => 'literal',
                    ]
                ),
                'expectedData' => [
                    'name' => 'passed, single is assertion with data',
                    'status' => $statusPassed,
                    'statements' => [
                        $passedIsAssertionWithDataStatement->getData(),
                    ],
                    'data' => [
                        'expected_value' => 'literal',
                    ],
                ],
            ],
        ];
    }
}
