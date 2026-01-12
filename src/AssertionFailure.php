<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilModels\Model\StatementInterface;

readonly class AssertionFailure
{
    /**
     * @param non-empty-string $reason
     * @param array<mixed>     $context
     */
    public function __construct(
        public StatementInterface $statement,
        public string $reason,
        public AssertionFailureException $exception,
        public array $context = [],
    ) {}
}
