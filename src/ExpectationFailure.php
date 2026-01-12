<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilModels\Model\Assertion\AssertionInterface;

readonly class ExpectationFailure
{
    public function __construct(
        public AssertionInterface $assertion,
        public bool|string $expected,
        public bool|string $examined,
    ) {}
}
