<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

class TestOutput
{
    private string $testPath;

    public function __construct(string $testPath)
    {
        $this->testPath = $testPath;
    }

    public function hasPath(string $path): bool
    {
        return $this->testPath === $path;
    }

    public function getPath(): string
    {
        return $this->testPath;
    }
}
