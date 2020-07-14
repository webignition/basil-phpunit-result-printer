<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\ExceptionData;

interface ExceptionDataInterface
{
    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
