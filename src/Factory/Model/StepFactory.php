<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model;

use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilPhpUnitResultPrinter\AssertionFailure;
use webignition\BasilPhpUnitResultPrinter\ExpectationFailure;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement\StatementFactory;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\ExceptionDataInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\State;
use webignition\BasilPhpUnitResultPrinter\StatementCollection;
use webignition\BasilRunnerDocuments\Step;

readonly class StepFactory
{
    public function __construct(
        private StatementFactory $statementFactory,
        private NewExceptionDataFactory $exceptionDataFactory,
    ) {}

    public static function createFactory(): self
    {
        return new StepFactory(
            StatementFactory::createFactory(),
            NewExceptionDataFactory::createFactory(),
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
            $this->fooCreateStatements(
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
    private function fooCreateStatements(
        StatementCollection $statementCollection,
        ?ExpectationFailure $expectationFailure,
        ?AssertionFailure $assertionFailure,
    ): array {
        /** @var StatementInterface[] $statements */
        $statements = [];

        $passedStatements = $statementCollection->getHandledStatements();
        foreach ($passedStatements as $passedStatement) {
            if ($passedStatement instanceof ActionInterface) {
                $statements[] = $this->statementFactory->createForAction(
                    $passedStatement,
                    new Status(Status::STATUS_PASSED),
                );
            }

            if ($passedStatement instanceof AssertionInterface) {
                $statements[] = $this->statementFactory->createForPassedAssertion($passedStatement);
            }
        }

        $failedStatement = $statementCollection->getFailedStatement();

        if ($assertionFailure instanceof AssertionFailure) {
            $statement = null;

            if ($failedStatement instanceof ActionInterface) {
                $statement = $this->statementFactory->createForAction(
                    $failedStatement,
                    new Status(Status::STATUS_FAILED),
                );

                $exceptionData = $this->exceptionDataFactory->create($assertionFailure->exception);
                if ($exceptionData instanceof ExceptionDataInterface) {
                    $statement = $statement->withExceptionData($exceptionData);
                }
            }

            if ($failedStatement instanceof AssertionInterface) {
                $statement = $this->statementFactory->createForAssertionFailure($failedStatement);
                $exceptionData = null;

                if ('locator-invalid' === $assertionFailure->reason) {
                    $locator = $assertionFailure->context['locator'] ?? null;
                    $locator = is_string($locator) ? $locator : null;

                    $type = $assertionFailure->context['type'] ?? null;
                    $type = is_string($type) ? $type : null;

                    if (is_string($locator) && is_string($type)) {
                        $exceptionData = $this->exceptionDataFactory->createForInvalidLocator($locator, $type);
                    }
                } else {
                    $exceptionData = $this->exceptionDataFactory->create($assertionFailure->exception);
                }

                if ($exceptionData instanceof ExceptionDataInterface) {
                    $statement = $statement->withExceptionData($exceptionData);
                }
            }

            if ($statement instanceof StatementInterface) {
                $statements[] = $statement;
            }
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
