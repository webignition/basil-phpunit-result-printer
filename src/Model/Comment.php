<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

class Comment extends AbstractEncapsulatedContentLine
{
    public const START = '<comment>';
    public const END = '</comment>';

    public function __construct(string $content)
    {
        parent::__construct($content);
    }

    protected function getRenderTemplate(): string
    {
        return self::START . '%s' . self::END;
    }
}
