<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement;

use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Model\StatementInterface as StatementModelInterface;
use webignition\BasilPhpUnitResultPrinter\Enum\StatementType;
use webignition\BasilPhpUnitResultPrinter\ExpectationFailure;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\AssertionFailureSummaryFactory;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Statement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;

class StatementFactory
{
    public function __construct(
        private TransformationFactory $transformationFactory,
        private AssertionFailureSummaryFactory $assertionFailureSummaryFactory
    ) {}

    public static function createFactory(): self
    {
        return new StatementFactory(
            new TransformationFactory(),
            AssertionFailureSummaryFactory::createFactory()
        );
    }

    public function create(StatementModelInterface $statement, Status $status): StatementInterface
    {
        $statementType = $statement instanceof ActionInterface
            ? StatementType::ACTION
            : StatementType::ASSERTION;

        return new Statement(
            $statementType,
            $statement->getSource(),
            (string) $status,
        )->withTransformations(
            $this->transformationFactory->createTransformations($statement)
        );
    }

    public function createForPassedAssertion(AssertionInterface $assertion): StatementInterface
    {
        return new Statement(
            StatementType::ASSERTION,
            $assertion->getSource(),
            (string) new Status(Status::STATUS_PASSED),
        )->withTransformations(
            $this->transformationFactory->createTransformations($assertion)
        );
    }

    public function createForExpectationFailure(ExpectationFailure $expectationFailure): ?StatementInterface
    {
        $expectedValue = $expectationFailure->expected;
        $examinedValue = $expectationFailure->examined;

        if (is_bool($expectedValue)) {
            $expectedValue = $expectedValue ? 'true' : 'false';
        }

        if (is_bool($examinedValue)) {
            $examinedValue = $examinedValue ? 'true' : 'false';
        }

        $failureSummary = $this->assertionFailureSummaryFactory->create(
            $expectationFailure->assertion,
            $expectedValue,
            $examinedValue
        );

        if ($failureSummary instanceof AssertionFailureSummaryInterface) {
            return new Statement(
                StatementType::ASSERTION,
                $expectationFailure->assertion->getSource(),
                (string) new Status(Status::STATUS_FAILED),
            )
                ->withFailureSummary(
                    $failureSummary
                )
                ->withTransformations(
                    $this->transformationFactory->createTransformations($expectationFailure->assertion)
                )
            ;
        }

        return null;
    }

    public function createForAssertionFailure(AssertionInterface $assertion): StatementInterface
    {
        return new Statement(
            StatementType::ASSERTION,
            $assertion->getSource(),
            (string) new Status(Status::STATUS_FAILED),
        )->withTransformations(
            $this->transformationFactory->createTransformations($assertion),
        );
    }
}
