<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Source;

use webignition\BasilPhpUnitResultPrinter\Model\Scalar;

class ScalarSource extends AbstractSource
{
    private const TYPE = 'scalar';

    public function __construct(
        private Scalar $body
    ) {}

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getBody(): Scalar
    {
        return $this->body;
    }
}
