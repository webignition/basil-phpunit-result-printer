<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Services;

class FixtureLoader
{
    public static function load(string $path): ?string
    {
        $fullPath = realpath(__DIR__ . '/../Fixtures' . $path);

        if (is_string($fullPath)) {
            return (string) file_get_contents($fullPath);
        }

        return null;
    }
}
