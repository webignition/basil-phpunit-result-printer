<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\Comment;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableInterface;

class ScalarIsRegExpSummary implements RenderableInterface
{
    private Comment $regexp;

    public function __construct(string $regexp)
    {
        $this->regexp = new Comment($regexp);
    }

    public function render(): string
    {
        return sprintf(
            '* %s %s',
            $this->regexp->render(),
            'is not a valid regular expression'
        );
    }
}
