<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

readonly class FailedAction
{
    /**
     * @param non-empty-string $action
     * @param non-empty-string $reason
     */
    public function __construct(
        public string $action,
        public string $reason,
        public FailedActionException $exception,
    ) {}
}
