<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilModels\Model\StatementFactory;
use webignition\BasilModels\Model\StatementInterface;
use webignition\BasilModels\Model\UnknownEncapsulatedStatementException;

readonly class AssertionFailureExtractor
{
    public function __construct(
        private StatementFactory $statementFactory,
        private AssertionFailureExceptionExtractor $exceptionExtractor,
    ) {}

    /**
     * @param array<mixed> $data
     */
    public function extract(array $data): ?AssertionFailure
    {
        $statementData = $data['statement'] ?? [];
        $statementData = is_array($statementData) ? $statementData : [];

        try {
            $statement = $this->statementFactory->createFromArray($statementData);
        } catch (UnknownEncapsulatedStatementException) {
            $statement = null;
        }

        if (!$statement instanceof StatementInterface) {
            return null;
        }

        $reason = $data['reason'] ?? '';
        $reason = is_string($reason) ? $reason : '';
        $reason = trim($reason);
        if ('' === $reason) {
            return null;
        }

        $exceptionData = $data['exception'] ?? [];
        $exceptionData = is_array($exceptionData) ? $exceptionData : [];

        $exception = $this->exceptionExtractor->extract($exceptionData);
        if (null === $exception) {
            return null;
        }

        $context = $data['context'] ?? [];
        $context = is_array($context) ? $context : [];

        return new AssertionFailure($statement, $reason, $exception, $context);
    }
}
