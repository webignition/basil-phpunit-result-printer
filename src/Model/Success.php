<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

class Success extends AbstractEncapsulatedContentLine
{
    public const START = '<success>';
    public const END = '</success>';

    public function __construct(string $content)
    {
        parent::__construct($content);
    }

    protected function getRenderTemplate(): string
    {
        return self::START . '%s' . self::END;
    }
}
