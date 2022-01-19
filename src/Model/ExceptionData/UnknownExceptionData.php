<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\ExceptionData;

class UnknownExceptionData extends AbstractExceptionData
{
    private const TYPE = 'unknown';

    public function __construct(
        private string $class,
        private string $message
    ) {
    }

    protected function getType(): string
    {
        return self::TYPE;
    }

    protected function getBody(): array
    {
        return [
            'class' => $this->class,
            'message' => $this->message,
        ];
    }
}
