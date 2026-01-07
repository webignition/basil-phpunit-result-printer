<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\StatementFactory;
use webignition\BasilModels\Model\UnknownEncapsulatedStatementException;

readonly class FailedActionExtractor
{
    public function __construct(
        private StatementFactory $statementFactory,
        private FailedActionExceptionExtractor $exceptionExtractor,
    ) {}

    /**
     * @param array<mixed> $data
     */
    public function extract(array $data): ?FailedAction
    {
        $statementData = $data['statement'] ?? [];
        $statementData = is_array($statementData) ? $statementData : [];

        try {
            $statement = $this->statementFactory->createFromArray($statementData);
        } catch (UnknownEncapsulatedStatementException) {
            $statement = null;
        }

        if (!$statement instanceof ActionInterface) {
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

        return new FailedAction($statement, $reason, $exception);
    }
}
