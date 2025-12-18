<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Code\Throwable;
use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class TestDataExtractor
{
    public function extract(TestMethod $testMethod, ?Throwable $throwable = null): TestData
    {
        $stepName = 'step name';
        $statements = [];
        $failedAssertion = null;

        $reflectionClass = new \ReflectionClass($testMethod->className());
        $reflectionMethod = $reflectionClass->getMethod($testMethod->methodName());

        $statementsAttributes = $reflectionMethod->getAttributes(Statements::class);
        $statementsAttribute = $statementsAttributes[0];

        return new TestData(
            $this->getStepName($reflectionMethod),
            $statementsAttribute->newInstance()->statements,
            $failedAssertion
        );
    }


    /**
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return non-empty-string
     */
    private function getStepName(\ReflectionMethod $reflectionMethod): string
    {
        $stepNameAttributes = $reflectionMethod->getAttributes(StepName::class);
        $stepNameAttribute = $stepNameAttributes[0];

        $name = $stepNameAttribute->newInstance()->name;
        \assert('' !== $name);

        return $name;
    }
}
