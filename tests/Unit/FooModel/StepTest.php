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
            'passed' => [
                'name' => 'passed, single assertion',
                'status' => $statusPassed,
                'statements' => [
                    \Mockery::mock(PassedAssertionStatement::class),
                ],
            ],
            'failed' => [
                'name' => 'failed, single assertion',
                'status' => $statusFailed,
                'statements' => [
                    \Mockery::mock(FailedAssertionStatement::class),
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

        $passedActionStatement = $statementFactory->createForPassedAction(
            $clickAction
        ) ?? \Mockery::mock(ActionStatement::class);

        $passedAssertionStatement = $statementFactory->createForPassedAssertion(
            $existsAssertion
        ) ?? \Mockery::mock(PassedAssertionStatement::class);

        $failedAssertionStatement = $statementFactory->createForFailedAssertion(
            $existsAssertion,
            '',
            ''
        ) ?? \Mockery::mock(FailedAssertionStatement::class);

        $statusPassed = (string) new Status(Status::STATUS_PASSED);
        $statusFailed = (string) new Status(Status::STATUS_FAILED);

        return [
            'passed, single assertion' => [
                'step' => new Step(
                    'passed, single assertion',
                    $statusPassed,
                    [
                        $passedAssertionStatement,
                    ]
                ),
                'expectedData' => [
                    'name' => 'passed, single assertion',
                    'status' => $statusPassed,
                    'statements' => [
                        $passedAssertionStatement->getData(),
                    ],
                ],
            ],
            'passed, single action, single assertion' => [
                'step' => new Step(
                    'passed, single action, single assertion',
                    $statusPassed,
                    [
                        $passedActionStatement,
                        $passedAssertionStatement,
                    ]
                ),
                'expectedData' => [
                    'name' => 'passed, single action, single assertion',
                    'status' => $statusPassed,
                    'statements' => [
                        $passedActionStatement->getData(),
                        $passedAssertionStatement->getData(),
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
        ];
    }
}
