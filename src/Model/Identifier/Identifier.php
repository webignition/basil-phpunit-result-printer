<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Identifier;

class Identifier
{
    public function __construct(
        private string $source,
        private Properties $properties
    ) {
    }

    public function isAttribute(): bool
    {
        return $this->properties->hasAttribute();
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
