<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model;

use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilPhpUnitResultPrinter\AssertionFailure;
use webignition\BasilPhpUnitResultPrinter\ExpectationFailure;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement\StatementFactory;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\ExceptionDataInterface;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\UnknownExceptionData;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\State;
use webignition\BasilPhpUnitResultPrinter\StatementCollection;
use webignition\BasilRunnerDocuments\Step;

readonly class StepFactory
{
    public function __construct(
        private StatementFactory $statementFactory,
        private ExceptionDataFactory $exceptionDataFactory,
    ) {}

    public static function createFactory(): self
    {
        return new StepFactory(
            StatementFactory::createFactory(),
            ExceptionDataFactory::createFactory(),
        );
    }

    /**
     * @param null|array<mixed> $data
     */
    public function create(string $stepName, State $state, StatementCollection $statements, ?array $data): Step
    {
        return new Step(
            $stepName,
            (string) $state->getStatus(),
            $this->createStatements(
                $statements,
                $state->getExpectationFailure(),
                $state->getAssertionFailure(),
            ),
            $data,
        );
    }

    /**
     * @return StatementInterface[]
     */
    private function createStatements(
        StatementCollection $statementCollection,
        ?ExpectationFailure $expectationFailure,
        ?AssertionFailure $assertionFailure,
    ): array {
        /** @var StatementInterface[] $statements */
        $statements = [];

        $passedStatements = $statementCollection->getHandledStatements();
        foreach ($passedStatements as $passedStatement) {
            $statements[] = $this->statementFactory->create($passedStatement, new Status(Status::STATUS_PASSED));
        }

        $failedStatement = $statementCollection->getFailedStatement();

        if ($failedStatement instanceof ActionInterface && $assertionFailure instanceof AssertionFailure) {
            $statement = $this->statementFactory->create($failedStatement, new Status(Status::STATUS_FAILED));
            $exception = $assertionFailure->exception;

            $statement = $statement->withExceptionData(
                new UnknownExceptionData($exception->class, $exception->message)
            );

            $statements[] = $statement;
        }

        if ($failedStatement instanceof AssertionInterface && $assertionFailure instanceof AssertionFailure) {
            $statement = $this->statementFactory->createForAssertionFailure($failedStatement);
            $exception = $assertionFailure->exception;
            $exceptionData = new UnknownExceptionData($exception->class, $exception->message);

            if ('locator-invalid' === $assertionFailure->reason) {
                $locator = $assertionFailure->context['locator'] ?? null;
                $locator = is_string($locator) ? $locator : null;

                $type = $assertionFailure->context['type'] ?? null;
                $type = is_string($type) ? $type : null;

                if (is_string($locator) && is_string($type)) {
                    $exceptionData = $this->exceptionDataFactory->createForInvalidLocator($locator, $type);
                }
            }

            if ($exceptionData instanceof ExceptionDataInterface) {
                $statement = $statement->withExceptionData($exceptionData);
            }

            $statements[] = $statement;
        }

        if ($expectationFailure instanceof ExpectationFailure) {
            $statement = $this->statementFactory->createForExpectationFailure($expectationFailure);

            if ($statement instanceof StatementInterface) {
                $statements[] = $statement;
            }
        }

        return $statements;
    }
}
