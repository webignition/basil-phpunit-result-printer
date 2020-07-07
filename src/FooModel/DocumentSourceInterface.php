<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel;

interface DocumentSourceInterface
{
    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
