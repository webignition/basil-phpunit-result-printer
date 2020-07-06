<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Source;

use webignition\BasilPhpUnitResultPrinter\FooModel\SourceBodyInterface;

interface SourceInterface
{
    public function getType(): string;
    public function getBody(): SourceBodyInterface;

    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
