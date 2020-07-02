<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

class Literal implements RenderableInterface
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function render(): string
    {
        return $this->content;
    }
}
