<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement;

use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilPhpUnitResultPrinter\Enum\StatementType;
use webignition\BasilPhpUnitResultPrinter\ExpectationFailure;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\AssertionFailureSummaryFactory;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\FailedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\PassedAssertionStatement;
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

    public function createForPassedAction(ActionInterface $action): StatementInterface
    {
        return $this->createForAction($action, Status::STATUS_PASSED);
    }

    public function createForFailedAction(ActionInterface $action): StatementInterface
    {
        return $this->createForAction($action, Status::STATUS_FAILED);
    }

    public function createForPassedAssertion(AssertionInterface $assertion): StatementInterface
    {
        return new PassedAssertionStatement(
            $assertion->getSource(),
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
            return new FailedAssertionStatement(
                $expectationFailure->assertion->getSource(),
                $failureSummary,
                $this->transformationFactory->createTransformations($expectationFailure->assertion)
            );
        }

        return null;
    }

    public function createForAssertionFailure(AssertionInterface $assertion): StatementInterface
    {
        return new FailedAssertionStatement(
            $assertion->getSource(),
            null,
            $this->transformationFactory->createTransformations($assertion),
        );
    }

    private function createForAction(ActionInterface $action, int $status): StatementInterface
    {
        return new Statement(
            StatementType::ACTION,
            $action->getSource(),
            (string) new Status($status),
            $this->transformationFactory->createTransformations($action)
        );
    }
}
