<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Statement;

class PassedAssertionStatement extends AbstractAssertionStatement
{
    private const STATUS = 'passed';

    public function __construct(string $source, array $transformations = [])
    {
        parent::__construct($source, self::STATUS, $transformations);
    }
}
