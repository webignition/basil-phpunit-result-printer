<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber as FinishedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

readonly class FinishedSubscriber implements FinishedSubscriberInterface
{
    public function __construct(private Printer $printer) {}

    public function notify(Finished $event): void
    {
        $this->printer->print($event::class);
        $this->printer->print("\n");

        $test = $event->test();
        \assert($test instanceof TestMethod);

        $this->printer->print($this->getStepName($test) . "\n");

        foreach ($this->getStatementsData($test) as $statementData) {
            $this->printer->print(json_encode($statementData) . "\n");
        }
    }

    private function getStepName(TestMethod $testMethod): string
    {
        try {
            $reflectionClass = new \ReflectionClass($testMethod->className());
            $reflectionMethod = $reflectionClass->getMethod($testMethod->methodName());
        } catch (\ReflectionException) {
            return '';
        }

        $stepNameAttributes = $reflectionMethod->getAttributes(StepName::class);
        $stepNameAttribute = $stepNameAttributes[0];

        return $stepNameAttribute->newInstance()->name;
    }

    /**
     * @return array<mixed>
     */
    private function getStatementsData(TestMethod $testMethod): array
    {
        try {
            $reflectionClass = new \ReflectionClass($testMethod->className());
            $reflectionMethod = $reflectionClass->getMethod($testMethod->methodName());
        } catch (\ReflectionException $e) {
            return [];
        }

        $stepNameAttributes = $reflectionMethod->getAttributes(Statements::class);
        $stepNameAttribute = $stepNameAttributes[0];

        return $stepNameAttribute->newInstance()->statements;
    }
}
