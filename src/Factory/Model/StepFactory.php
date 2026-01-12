<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model;

use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Model\DataSet\DataSetInterface;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement\StatementFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilRunnerDocuments\Step;

class StepFactory
{
    public function __construct(
        private StatementFactory $statementFactory,
        private ExceptionDataFactory $exceptionDataFactory
    ) {}

    public static function createFactory(): self
    {
        return new StepFactory(
            StatementFactory::createFactory(),
            ExceptionDataFactory::createFactory()
        );
    }

    public function create(object $testCase): Step
    {
        return new Step(
            $testCase->getBasilStepName(),
            (string) new Status($testCase->getStatus()),
            $this->createStatements($testCase),
            $this->createData($testCase)
        );
    }

    /**
     * @return StatementInterface[]
     */
    private function createStatements(object $testCase): array
    {
        /** @var StatementInterface[] $statements */
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
            $statement = $this->statementFactory->createForExpectationFailure(
                $failedStatement,
                (string) $testCase->getExpectedValue(),
                (string) $testCase->getExaminedValue()
            );

            if ($statement instanceof StatementInterface) {
                $statements[] = $statement;
            }
        }

        $lastException = $testCase->getLastException();
        if ($lastException instanceof \Throwable) {
            if (count($statements) > 0) {
                $finalStatement = array_pop($statements);

                $exceptionData = $this->exceptionDataFactory->create($lastException);
                $finalStatement = $finalStatement->withExceptionData($exceptionData);
                $statements[] = $finalStatement;
            }
        }

        return $statements;
    }

    /**
     * @return null|array<mixed>
     */
    private function createData(object $testCase): ?array
    {
        $dataSet = $testCase->getCurrentDataSet();

        if ($dataSet instanceof DataSetInterface) {
            return $dataSet->getData();
        }

        return null;
    }
}
