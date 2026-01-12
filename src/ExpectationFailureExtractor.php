<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Model\StatementFactory;
use webignition\BasilModels\Model\UnknownEncapsulatedStatementException;

readonly class ExpectationFailureExtractor
{
    public function __construct(
        private StatementFactory $statementFactory,
    ) {}

    /**
     * @param array<mixed> $data
     */
    public function extract(array $data): ?ExpectationFailure
    {
        $statementData = $data['statement'] ?? [];
        $statementData = is_array($statementData) ? $statementData : [];

        try {
            $assertion = $this->statementFactory->createFromArray($statementData);
        } catch (UnknownEncapsulatedStatementException) {
            return null;
        }

        if (!$assertion instanceof AssertionInterface) {
            return null;
        }

        $expected = $data['expected'];
        $examined = $data['examined'];

        if (!is_string($expected) && !is_bool($expected)) {
            return null;
        }

        if (!is_string($examined) && !is_bool($examined)) {
            return null;
        }

        return new ExpectationFailure($assertion, $expected, $examined);
    }
}
