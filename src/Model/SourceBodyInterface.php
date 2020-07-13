<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

interface SourceBodyInterface
{
    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
