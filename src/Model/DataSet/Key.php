<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\DataSet;

use webignition\BasilPhpUnitResultPrinter\Model\RenderableInterface;

class Key implements RenderableInterface
{
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function render(): string
    {
        return sprintf(
            '$%s',
            $this->key
        );
    }
}
