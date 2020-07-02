<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\Comment;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableInterface;

class WithValue implements RenderableInterface
{
    private Comment $value;

    public function __construct(string $value)
    {
        $this->value = new Comment($value);
    }

    public function render(): string
    {
        return sprintf(
            'with value %s',
            $this->value->render()
        );
    }
}
