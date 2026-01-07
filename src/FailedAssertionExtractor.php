<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Model\StatementFactory;
use webignition\BasilModels\Model\UnknownEncapsulatedStatementException;

readonly class FailedAssertionExtractor
{
    public function __construct(
        private StatementFactory $statementFactory,
    ) {}

    /**
     * @param array<mixed> $data
     */
    public function extract(array $data): ?AssertionInterface
    {
        try {
            $statement = $this->statementFactory->createFromArray($data);
        } catch (UnknownEncapsulatedStatementException) {
            return null;
        }

        return $statement instanceof AssertionInterface ? $statement : null;
    }
}
