<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

interface RenderableInterface
{
    public function render(): string;
}
