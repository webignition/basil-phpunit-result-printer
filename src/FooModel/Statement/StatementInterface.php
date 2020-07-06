<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Statement;

interface StatementInterface
{
    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
