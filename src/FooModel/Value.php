<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel;

class Value
{
    private string $value;
    private Source $source;

    public function __construct(string $value, Source $source)
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
