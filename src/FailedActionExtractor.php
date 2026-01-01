<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Event\Code\Throwable;

readonly class FailedActionExtractor
{
    public function __construct(
        private FailedActionExceptionExtractor $exceptionExtractor,
    ) {}

    public function extract(Throwable $throwable): ?FailedAction
    {
        $data = json_decode($throwable->message(), true);
        $data = is_array($data) ? $data : [];

        $statementData = $data['statement'] ?? [];
        $statementData = is_array($statementData) ? $statementData : [];

        $statement = $this->extractStatement($statementData);
        if (null === $statement) {
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

    /**
     * @param array<mixed> $data
     *
     * @return ?non-empty-string
     */
    private function extractStatement(array $data): ?string
    {
        $statement = $data['statement'] ?? '';
        $statement = is_string($statement) ? $statement : '';
        $statement = trim($statement);

        if ('' === $statement) {
            return null;
        }

        return $statement;
    }
}
