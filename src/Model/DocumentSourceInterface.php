<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

interface DocumentSourceInterface
{
    public function getType(): string;

    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
