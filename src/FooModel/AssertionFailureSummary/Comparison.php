<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\FooModel\Value;

class Comparison
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

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [
            'operator' => $this->operator,
            'expected' => $this->expected->getData(),
            'actual' => $this->actual->getData(),
        ];
    }
}
