<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\Value;

class Comparison implements AssertionFailureSummaryInterface
{
    private string $operator;
    private Value $expected;
    private Value $actual;

    public function __construct(string $operator, Value $expected, Value $actual)
    {
        $this->operator = $operator;
        $this->expected = $expected;
        $this->actual = $actual;
    }

    public function getData(): array
    {
        return [
            'operator' => $this->operator,
            'expected' => $this->expected->getData(),
            'actual' => $this->actual->getData(),
        ];
    }
}
