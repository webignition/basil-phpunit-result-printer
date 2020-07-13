<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

interface DocumentSourceInterface
{
    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
