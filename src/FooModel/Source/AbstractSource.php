<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Source;

abstract class AbstractSource implements SourceInterface
{
    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [
            'type' => $this->getType(),
            'body' => $this->getBody()->getData(),
        ];
    }
}
