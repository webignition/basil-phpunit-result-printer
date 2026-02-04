<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\ExpectationFailure;

use webignition\BasilModels\Model\Statement\Assertion\AssertionInterface;
use webignition\BasilModels\Model\Statement\StatementFactory;
use webignition\BasilModels\Model\Statement\UnknownEncapsulatedStatementException;

readonly class ExpectationFailureFactory
{
    public function __construct(
        private StatementFactory $statementFactory,
    ) {}

    /**
     * @param array<mixed> $data
     */
    public function create(array $data): ?ExpectationFailure
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
