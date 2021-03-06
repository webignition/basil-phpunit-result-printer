<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;

class Existence implements AssertionFailureSummaryInterface
{
    private string $operator;
    private NodeSource $source;

    public function __construct(string $operator, NodeSource $source)
    {
        $this->operator = $operator;
        $this->source = $source;
    }

    public function getData(): array
    {
        return [
            'operator' => $this->operator,
            'source' => $this->source->getData(),
        ];
    }
}
