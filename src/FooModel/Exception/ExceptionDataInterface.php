<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Exception;

interface ExceptionDataInterface
{
    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
