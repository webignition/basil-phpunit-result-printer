<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Identifier;

class Identifier
{
    private string $source;
    private Properties $properties;

    public function __construct(string $source, Properties $properties)
    {
        $this->source = $source;
        $this->properties = $properties;
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [
            'source' => $this->source,
            'properties' => $this->properties->getData(),
        ];
    }
}
