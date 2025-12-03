<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

use webignition\BasilPhpUnitResultPrinter\Model\Source\SourceInterface;

class Value
{
    public function __construct(
        private string $value,
        private SourceInterface $source
    ) {}

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [
            'value' => $this->value,
            'source' => $this->source->getData(),
        ];
    }
}
