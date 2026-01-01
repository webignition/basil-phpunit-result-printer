<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Event\Code\TestMethod;
use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class TestMetaDataExtractor
{
    public function extract(TestMethod $testMethod): TestMetaData
    {
        $reflectionClass = new \ReflectionClass($testMethod->className());
        $reflectionMethod = $reflectionClass->getMethod($testMethod->methodName());

        $stepNameAttributes = $reflectionMethod->getAttributes(StepName::class);
        $stepNameAttribute = $stepNameAttributes[0];

        $statementsAttributes = $reflectionMethod->getAttributes(Statements::class);
        $statementsAttribute = $statementsAttributes[0];

        return new TestMetaData(
            $stepNameAttribute->newInstance()->name,
            $statementsAttribute->newInstance()->statements,
        );
    }
}
