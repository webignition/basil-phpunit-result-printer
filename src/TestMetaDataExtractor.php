<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Code\Throwable;
use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class TestMetaDataExtractor
{
    public function extract(TestMethod $testMethod, ?Throwable $throwable = null): TestMetaData
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
            $this->getFailedAssertion($throwable)
        );
    }

    /**
     * @return null|non-empty-string
     */
    private function getFailedAssertion(?Throwable $throwable): ?string
    {
        if (null === $throwable) {
            return null;
        }

        $assertionFailureMessage = $throwable->message();

        $finalBracePosition = (int) strrpos($assertionFailureMessage, '}');

        $json = substr($assertionFailureMessage, 0, $finalBracePosition + 1);

        return '' === $json ? null : $json;
    }
}
