<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model;

use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\SourceFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\Comparison;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\IsRegExp;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\SourceInterface;
use webignition\BasilPhpUnitResultPrinter\FooModel\Value;

class AssertionFailureSummaryFactory
{
    private SourceFactory $sourceFactory;

    public function __construct(SourceFactory $sourceFactory)
    {
        $this->sourceFactory = $sourceFactory;
    }

    public static function createFactory(): self
    {
        return new AssertionFailureSummaryFactory(
            SourceFactory::createFactory()
        );
    }

    public function create(
        AssertionInterface $assertion,
        string $expectedValue,
        string $actualValue
    ): ?AssertionFailureSummaryInterface {
        $operator = $assertion->getOperator();

        if (in_array($operator, ['exists', 'not-exists'])) {
            $identifierString = $assertion->getIdentifier();
            $source = $this->sourceFactory->create($identifierString);

            if ($source instanceof NodeSource) {
                return new Existence($operator, $source);
            }

            return null;
        }

        if ('is-regexp' === $operator) {
            $identifierString = $assertion->getIdentifier();
            $source = $this->sourceFactory->create($identifierString);

            if ($source instanceof SourceInterface) {
                return new IsRegExp($actualValue, $source);
            }

            return null;
        }

        $expected = $this->sourceFactory->create((string) $assertion->getValue());
        $actual = $this->sourceFactory->create($assertion->getIdentifier());

        if ($expected instanceof SourceInterface && $actual instanceof SourceInterface) {
            return new Comparison(
                $operator,
                new Value($expectedValue, $expected),
                new Value($actualValue, $actual)
            );
        }

        return null;
    }
}
