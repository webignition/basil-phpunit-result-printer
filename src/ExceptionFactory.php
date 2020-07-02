<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilPhpUnitResultPrinter\Model\Exception\InvalidLocator;
use webignition\BasilPhpUnitResultPrinter\Model\Exception\Unknown;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableInterface;
use webignition\SymfonyDomCrawlerNavigator\Exception\InvalidLocatorException;

class ExceptionFactory
{
    public function create(\Throwable $exception): RenderableInterface
    {
        if ($exception instanceof InvalidLocatorException) {
            return new InvalidLocator($exception);
        }

        return new Unknown($exception);
    }
}
