<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Statement;

use webignition\BasilPhpUnitResultPrinter\FooModel\ExceptionData\ExceptionDataInterface;

interface StatementInterface
{
    public function withExceptionData(ExceptionDataInterface $exceptionData): StatementInterface;

    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
