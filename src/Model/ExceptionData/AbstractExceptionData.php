<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\ExceptionData;

abstract class AbstractExceptionData implements ExceptionDataInterface
{
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

    abstract protected function getType(): string;

    /**
     * @return array<mixed>
     */
    abstract protected function getBody(): array;
}
