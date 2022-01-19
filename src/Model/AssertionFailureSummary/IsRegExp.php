<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\Source\SourceInterface;

class IsRegExp implements AssertionFailureSummaryInterface
{
    private const OPERATOR = 'is-regexp';

    public function __construct(
        private string $value,
        private SourceInterface $source
    ) {
    }

    public function getData(): array
    {
        return [
            'operator' => self::OPERATOR,
            'value' => $this->value,
            'source' => $this->source->getData(),
        ];
    }
}
