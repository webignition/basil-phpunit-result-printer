<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\Comment;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableInterface;

class Property implements RenderableInterface
{
    private string $key;
    private Comment $value;
    private string $padding;

    public function __construct(string $key, string $value, string $padding = '')
    {
        $this->key = $key;
        $this->value = new Comment($value);
        $this->padding = $padding;
    }

    public function render(): string
    {
        return sprintf(
            '- %s: %s%s',
            $this->key,
            $this->padding,
            $this->value->render()
        );
    }
}
