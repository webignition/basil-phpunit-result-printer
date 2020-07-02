<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\IndentedContent;
use webignition\BasilPhpUnitResultPrinter\Model\Literal;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableCollection;
use webignition\DomElementIdentifier\ElementIdentifierInterface;

class ScalarToElementalComparisonSummary extends RenderableCollection
{
    public function __construct(
        ElementIdentifierInterface $valueIdentifier,
        string $operator,
        string $expectedValue,
        string $actualValue
    ) {
        $valueAncestorHierarchy = null === $valueIdentifier->getParentIdentifier()
            ? null
            : new IndentedContent(new AncestorHierarchy($valueIdentifier));

        parent::__construct([
            new ScalarValueComparedToElementalValue($actualValue, $operator, $valueIdentifier),
            new IndentedContent(new IdentifierProperties($valueIdentifier), 2),
            $valueAncestorHierarchy,
            new IndentedContent(new WithValue($expectedValue)),
            new Literal(''),
            new ScalarToScalarComparisonSummary($operator, $expectedValue, $actualValue)
        ]);
    }
}
