<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

readonly class FailedAssertion
{
    /**
     * @param non-empty-string $assertion
     */
    public function __construct(
        public string $assertion,
    ) {}
}
