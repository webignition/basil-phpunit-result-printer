<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Statement;

class Transformation
{
    public const TYPE_DERIVATION = 'derivation';
    public const TYPE_RESOLUTION = 'resolution';

    public function __construct(
        private string $type,
        private string $source
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [
            'type' => $this->type,
            'source' => $this->source,
        ];
    }
}
