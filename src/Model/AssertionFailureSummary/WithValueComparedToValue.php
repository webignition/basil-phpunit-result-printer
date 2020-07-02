<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\Comment;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableInterface;

class WithValueComparedToValue implements RenderableInterface
{
    private WithValue $withValue;
    private Comment $expectedValue;
    private ComparisonOperator $comparisonOperator;

    public function __construct(string $actualValue, string $expectedValue, string $operator)
    {
        $this->withValue = new WithValue($actualValue);
        $this->expectedValue = new Comment($expectedValue);
        $this->comparisonOperator = new ComparisonOperator($operator);
    }

    public function render(): string
    {
        return sprintf(
            '%s %s %s',
            $this->withValue->render(),
            $this->comparisonOperator->render(),
            $this->expectedValue->render()
        );
    }
}
