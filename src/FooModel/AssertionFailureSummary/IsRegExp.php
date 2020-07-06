<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\FooModel\Source\SourceInterface;

class IsRegExp implements AssertionFailureSummaryInterface
{
    private const OPERATOR = 'is-regexp';

    private string $value;
    private SourceInterface $source;

    public function __construct(string $value, SourceInterface $source)
    {
        $this->value = $value;
        $this->source = $source;
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
