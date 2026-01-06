<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Event\Code\Throwable;

readonly class JsonExtractor
{
    public function extract(Throwable $throwable): string
    {
        $content = $throwable->message();

        $finalBracePosition = (int) strrpos($content, '}');

        return substr($content, 0, $finalBracePosition + 1);
    }
}
