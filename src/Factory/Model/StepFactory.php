<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model;

use webignition\BaseBasilTestCase\BasilTestCaseInterface;
use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement\StatementFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\FooModel\Status;
use webignition\BasilPhpUnitResultPrinter\FooModel\Step;

class StepFactory
{
    private StatementFactory $statementFactory;

    public function __construct(StatementFactory $statementFactory)
    {
        $this->statementFactory = $statementFactory;
    }

    public static function createFactory(): self
    {
        return new StepFactory(
            StatementFactory::createFactory()
        );
    }

    public function create(BasilTestCaseInterface $testCase): Step
    {
        return new Step(
            $testCase->getBasilStepName(),
            (string) new Status($testCase->getStatus()),
            $this->createStatements($testCase)
        );
    }

    /**
     * @param BasilTestCaseInterface $testCase
     *
     * @return StatementInterface[]
     */
    private function createStatements(BasilTestCaseInterface $testCase): array
    {
        $statements = [];

        $failedStatement = null;
        $handledStatements = $testCase->getHandledStatements();

        if (Status::STATUS_PASSED !== $testCase->getStatus()) {
            $failedStatement = array_pop($handledStatements);
        }

        $passedStatements = $handledStatements;

        foreach ($passedStatements as $passedStatement) {
            if ($passedStatement instanceof ActionInterface) {
                $statements[] = $this->statementFactory->createForPassedAction($passedStatement);
            }

            if ($passedStatement instanceof AssertionInterface) {
                $statements[] = $this->statementFactory->createForPassedAssertion($passedStatement);
            }
        }

        if ($failedStatement instanceof ActionInterface) {
            $statements[] = $this->statementFactory->createForFailedAction($failedStatement);
        }

        if ($failedStatement instanceof AssertionInterface) {
            $statement = $this->statementFactory->createForFailedAssertion(
                $failedStatement,
                (string) $testCase->getExpectedValue(),
                (string) $testCase->getExaminedValue()
            );

            if ($statement instanceof StatementInterface) {
                $statements[] = $statement;
            }
        }

        return $statements;
    }
}