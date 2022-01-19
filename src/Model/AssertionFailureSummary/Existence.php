<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;

class Existence implements AssertionFailureSummaryInterface
{
    public function __construct(
        private string $operator,
        private NodeSource $source
    ) {
    }

    public function getData(): array
    {
        return [
            'operator' => $this->operator,
            'source' => $this->source->getData(),
        ];
    }
}
