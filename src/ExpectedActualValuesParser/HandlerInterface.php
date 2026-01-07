<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\ExpectedActualValuesParser;

use PHPUnit\Event\Test\Failed;

interface HandlerInterface
{
    /**
     * @return null|array{'expected': string, 'actual': string}
     */
    public function handle(Failed $event, string $content): ?array;
}
