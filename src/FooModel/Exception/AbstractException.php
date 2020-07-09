<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Exception;

abstract class AbstractException implements ExceptionDataInterface
{
    abstract protected function getType(): string;

    /**
     * @return array<mixed>
     */
    abstract protected function getBody(): array;

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [
            'type' => $this->getType(),
            'body' => $this->getBody(),
        ];
    }
}
