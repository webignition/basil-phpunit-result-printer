<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\AssertionFailure;

readonly class Exception
{
    /**
     * @param non-empty-string $class
     * @param non-empty-string $message
     */
    public function __construct(
        public string $class,
        public int $code,
        public string $message,
    ) {}
}
