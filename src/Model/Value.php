<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

use webignition\BasilPhpUnitResultPrinter\Model\Source\SourceInterface;

class Value
{
    private string $value;
    private SourceInterface $source;

    public function __construct(string $value, SourceInterface $source)
    {
        $this->value = $value;
        $this->source = $source;
    }

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
