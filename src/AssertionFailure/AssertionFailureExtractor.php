<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\AssertionFailure;

use webignition\BasilModels\Model\Statement\StatementFactory;
use webignition\BasilModels\Model\Statement\StatementInterface;
use webignition\BasilModels\Model\Statement\UnknownEncapsulatedStatementException;

readonly class AssertionFailureExtractor
{
    public function __construct(
        private StatementFactory $statementFactory,
        private ExceptionFactory $exceptionExtractor,
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

        $exceptionData = $data['exception'] ?? [];
        $exceptionData = is_array($exceptionData) ? $exceptionData : [];

        $exception = $this->exceptionExtractor->create($exceptionData);
        if (null === $exception) {
            return null;
        }

        $context = $data['context'] ?? [];
        $context = is_array($context) ? $context : [];

        return new AssertionFailure($statement, $exception, $context);
    }
}
