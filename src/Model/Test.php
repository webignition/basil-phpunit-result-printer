<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

class Test implements DocumentSourceInterface
{
    private const TYPE = 'test';

    private string $path;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function hasPath(string $path): bool
    {
        return $this->path === $path;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getData(): array
    {
        return [
            'path' => $this->path,
        ];
    }
}
