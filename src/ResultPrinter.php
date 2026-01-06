<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExceptionWrapper;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use PHPUnit\Util\Printer;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\StepFactory;
use webignition\BasilPhpUnitResultPrinter\Generator\GeneratorInterface;
use webignition\BasilPhpUnitResultPrinter\Generator\YamlGenerator;
use webignition\BasilRunnerDocuments\Exception;

class ResultPrinter extends Printer implements \PHPUnit\TextUI\ResultPrinter
{
    private GeneratorInterface $generator;
    private StepFactory $stepFactory;
    private ?Exception $uncaughtException = null;
    private bool $exceptionWritten = false;
    private ?Test $testWithException = null;

    public function __construct($out = null)
    {
        parent::__construct($out);

        $this->generator = new YamlGenerator();
        $this->stepFactory = StepFactory::createFactory();
    }

    public function addError(Test $test, \Throwable $t, float $time): void
    {
        //        if ($test instanceof BasilTestCaseInterface) {
        $exception = $t;

        if (
            $exception instanceof ExceptionWrapper
            && ($originalException = $exception->getOriginalException()) instanceof \Throwable
        ) {
            $exception = $originalException;
        }

        $test->setLastException($exception);
        $this->testWithException = $test;

        $step = $test->getBasilStepName();
        if ('' === $step) {
            $step = null;
        }

        $this->uncaughtException = Exception::createFromThrowable($exception, $step);
        //        }
    }

    public function addWarning(Test $test, Warning $e, float $time): void
    {
        // TODO: Implement addWarning() method.
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        // TODO: Implement addFailure() method.
    }

    public function addIncompleteTest(Test $test, \Throwable $t, float $time): void
    {
        // TODO: Implement addIncompleteTest() method.
    }

    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
        // TODO: Implement addRiskyTest() method.
    }

    public function addSkippedTest(Test $test, \Throwable $t, float $time): void
    {
        // TODO: Implement addSkippedTest() method.
    }

    /**
     * @param TestSuite<Test> $suite
     */
    public function startTestSuite(TestSuite $suite): void
    {
        // TODO: Implement startTestSuite() method.
    }

    /**
     * @param TestSuite<Test> $suite
     */
    public function endTestSuite(TestSuite $suite): void
    {
        // TODO: Implement endTestSuite() method.
    }

    public function startTest(Test $test): void
    {
        //        if ($test instanceof BasilTestCaseInterface) {
        if (null !== $test->getLastException() && '' === $test->getBasilStepName()) {
            $this->addError($test, $test->getLastException(), 0);
        }
        //        }
    }

    public function endTest(Test $test, float $time): void
    {
        //        if ($test instanceof BasilTestCaseInterface) {
        if ($this->uncaughtException instanceof Exception) {
            if (false === $this->exceptionWritten) {
                $this->write($this->generator->generate($this->uncaughtException->getData()));
                $this->exceptionWritten = true;
            }
        } else {
            $step = $this->stepFactory->create($test);
            $this->write($this->generator->generate($step->getData()));
        }
        //        }
    }

    public function printResult(TestResult $result): void
    {
        if (
            true === $this->exceptionWritten
//            && $this->testWithException instanceof BasilTestCaseInterface
            && ($lastException = $this->testWithException->getLastException()) instanceof \Throwable
        ) {
            $result->addError($this->testWithException, $lastException, 0);
        }
    }
}
