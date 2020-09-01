<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Statement;

class ActionStatement extends AbstractStatement
{
    private const TYPE = 'action';

    public function __construct(string $source, string $status, array $transformations = [])
    {
        parent::__construct(self::TYPE, $source, $status, $transformations);
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
