<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel;

class Test
{
    private string $path;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [
            'path' => $this->path,
        ];
    }
}
