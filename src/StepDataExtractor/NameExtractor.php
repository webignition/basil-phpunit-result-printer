<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\StepDataExtractor;

use PHPUnit\Event\Code\TestMethod;
use webignition\BaseBasilTestCase\Attribute\StepName;

readonly class NameExtractor
{
    public function extract(TestMethod $testMethod): string
    {
        $reflectionClass = new \ReflectionClass($testMethod->className());
        $reflectionMethod = $reflectionClass->getMethod($testMethod->methodName());

        $stepNameAttributes = $reflectionMethod->getAttributes(StepName::class);
        $stepNameAttribute = $stepNameAttributes[0];

        return $stepNameAttribute->newInstance()->name;
    }
}
