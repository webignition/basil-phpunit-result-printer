<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Source;

use webignition\BasilPhpUnitResultPrinter\Model\SourceBodyInterface;

interface SourceInterface
{
    public function getType(): string;

    public function getBody(): SourceBodyInterface;

    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
