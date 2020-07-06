<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel;

class Source
{
    public const TYPE_NODE = 'node';
    public const TYPE_SCALAR = 'scalar';

    private string $type;
    private SourceBodyInterface $body;

    public function __construct(string $type, SourceBodyInterface $body)
    {
        $this->type = $type;
        $this->body = $body;
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [
            'type' => $this->type,
            'body' => $this->body->getData(),
        ];
    }
}
