<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Statement;

use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\ExceptionDataInterface;

interface StatementInterface
{
    public function withExceptionData(ExceptionDataInterface $exceptionData): StatementInterface;

    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
