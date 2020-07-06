<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Source;

use webignition\BasilPhpUnitResultPrinter\FooModel\Scalar;

class ScalarSource extends AbstractSource
{
    private const TYPE = 'scalar';

    private Scalar $body;

    public function __construct(Scalar $body)
    {
        $this->body = $body;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getBody(): Scalar
    {
        return $this->body;
    }
}
