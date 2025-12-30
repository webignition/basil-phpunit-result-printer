<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

readonly class TestMetaData
{
    /**
     * @param non-empty-string                                                   $stepName
     * @param array{'type':'action'|'assertion', 'statement':non-empty-string}[] $statements
     * @param ?non-empty-string                                                  $failedAssertion
     */
    public function __construct(
        public string $stepName,
        public array $statements,
        public ?string $failedAssertion
    ) {}
}
