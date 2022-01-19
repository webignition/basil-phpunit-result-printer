<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement;

use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\AssertionFailureSummaryFactory;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\ActionStatement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\FailedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\PassedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;

class StatementFactory
{
    public function __construct(
        private TransformationFactory $transformationFactory,
        private AssertionFailureSummaryFactory $assertionFailureSummaryFactory
    ) {
    }

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

    public function createForFailedAssertion(
        AssertionInterface $assertion,
        string $expectedValue,
        string $actualValue
    ): ?StatementInterface {
        $failureSummary = $this->assertionFailureSummaryFactory->create($assertion, $expectedValue, $actualValue);

        if ($failureSummary instanceof AssertionFailureSummaryInterface) {
            return new FailedAssertionStatement(
                $assertion->getSource(),
                $failureSummary,
                $this->transformationFactory->createTransformations($assertion)
            );
        }

        return null;
    }

    private function createForAction(ActionInterface $action, int $status): StatementInterface
    {
        return new ActionStatement(
            $action->getSource(),
            (string) new Status($status),
            $this->transformationFactory->createTransformations($action)
        );
    }
}
