<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Exception;

class Unknown extends AbstractException
{
    private const TYPE = 'unknown';

    private string $class;
    private string $message;

    public function __construct(string $class, string $message)
    {
        $this->class = $class;
        $this->message = $message;
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
