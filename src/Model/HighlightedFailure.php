<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

class HighlightedFailure extends AbstractEncapsulatedContentLine
{
    public const START = '<highlighted-failure>';
    public const END = '</highlighted-failure>';

    public function __construct(string $content)
    {
        parent::__construct($content);
    }

    protected function getRenderTemplate(): string
    {
        return self::START . '%s' . self::END;
    }
}
