<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilModels\Model\Statement\StatementInterface;

readonly class AssertionFailure
{
    /**
     * @param array<mixed> $context
     */
    public function __construct(
        public StatementInterface $statement,
        public AssertionFailureException $exception,
        public array $context = [],
    ) {}
}
