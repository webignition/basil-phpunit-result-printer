<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model;

use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\SourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\Comparison;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\IsRegExp;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Model\Source\SourceInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Value;

class AssertionFailureSummaryFactory
{
    public function __construct(
        private SourceFactory $sourceFactory,
        private ValueFactory $valueFactory
    ) {
    }

    public static function createFactory(): self
    {
        return new AssertionFailureSummaryFactory(
            SourceFactory::createFactory(),
            ValueFactory::createFactory()
        );
    }

    public function create(
        AssertionInterface $assertion,
        string $expectedValue,
        string $actualValue
    ): ?AssertionFailureSummaryInterface {
        $operator = $assertion->getOperator();
        $identifierString = $assertion->getIdentifier();

        if (in_array($operator, ['exists', 'not-exists'])) {
            if (is_string($identifierString)) {
                $source = $this->sourceFactory->create($identifierString);

                if ($source instanceof NodeSource) {
                    return new Existence($operator, $source);
                }
            }

            return null;
        }

        if ('is-regexp' === $operator) {
            if (is_string($identifierString)) {
                $source = $this->sourceFactory->create($identifierString);

                if ($source instanceof SourceInterface) {
                    return new IsRegExp($actualValue, $source);
                }
            }

            return null;
        }

        $valueString = $assertion->getValue();

        if (is_string($valueString) && is_string($identifierString)) {
            $expectedValueObject = $this->valueFactory->create($expectedValue, $valueString);
            $actualValueObject = $this->valueFactory->create($actualValue, $identifierString);

            if ($expectedValueObject instanceof Value && $actualValueObject instanceof Value) {
                return new Comparison($operator, $expectedValueObject, $actualValueObject);
            }
        }

        return null;
    }
}
