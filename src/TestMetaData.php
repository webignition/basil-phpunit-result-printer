<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

readonly class TestMetaData
{
    /**
     * @param non-empty-string $stepName
     * @param string[]         $statements
     */
    public function __construct(
        public string $stepName,
        public array $statements,
    ) {}
}
