<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\ExpectedActualValuesParser;

use PHPUnit\Event\Test\Failed;

readonly class HasComparisonFailureHandler implements HandlerInterface
{
    public function handle(Failed $event, string $content): ?array
    {
        if (!$event->hasComparisonFailure()) {
            return null;
        }

        return [
            'expected' => $this->removeEncapsulatingSingleQuote($event->comparisonFailure()->expected()),
            'actual' => $this->removeEncapsulatingSingleQuote($event->comparisonFailure()->actual()),
        ];
    }

    private function removeEncapsulatingSingleQuote(string $value): string
    {
        if (str_starts_with($value, "'")) {
            $value = substr($value, 1);
        }

        if (str_ends_with($value, "'")) {
            $value = substr($value, 0, -1);
        }

        return $value;
    }
}
