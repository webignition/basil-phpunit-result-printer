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
use webignition\BaseBasilTestCase\BasilTestCaseInterface;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\StepFactory;
use webignition\BasilPhpUnitResultPrinter\Generator\GeneratorInterface;
use webignition\BasilPhpUnitResultPrinter\Generator\YamlGenerator;
use webignition\BasilPhpUnitResultPrinter\Model\Exception;
use webignition\BasilPhpUnitResultPrinter\Model\Test as TestOutput;

class ResultPrinter extends Printer implements \PHPUnit\TextUI\ResultPrinter
{
    private ?TestOutput $currentTestOutput = null;
    private GeneratorInterface $generator;
    private StepFactory $stepFactory;
    private ?Exception $exception = null;

    public function __construct($out = null)
    {
        parent::__construct($out);

        $this->generator = new YamlGenerator();
        $this->stepFactory = StepFactory::createFactory();
    }

    /**
     * @inheritDoc
     */
    public function addError(Test $test, \Throwable $t, float $time): void
    {
        $exception = $t;
        if ($exception instanceof ExceptionWrapper) {
            $exception = $exception->getOriginalException();
        }

        if ($exception instanceof \Exception) {
            $step = null;
            if ($test instanceof BasilTestCaseInterface) {
                $step = $test->getBasilStepName();

                if ('' === $step) {
                    $step = null;
                }
            }

            $this->exception = Exception::createFromThrowable($step, $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function addWarning(Test $test, Warning $e, float $time): void
    {
        // TODO: Implement addWarning() method.
    }

    /**
     * @inheritDoc
     */
    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        // TODO: Implement addFailure() method.
    }

    /**
     * @inheritDoc
     */
    public function addIncompleteTest(Test $test, \Throwable $t, float $time): void
    {
        // TODO: Implement addIncompleteTest() method.
    }

    /**
     * @inheritDoc
     */
    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
        // TODO: Implement addRiskyTest() method.
    }

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
    public function startTest(Test $test): void
    {
        if ($test instanceof BasilTestCaseInterface) {
            $testPath = $test::getBasilTestPath();

            $isNewTest = $this->currentTestOutput instanceof TestOutput
                ? false === $this->currentTestOutput->hasPath($testPath)
                : true;

            if ($isNewTest) {
                $currentTestOutput = new TestOutput($testPath);
                $this->write($this->generator->generate($currentTestOutput));
                $this->currentTestOutput = $currentTestOutput;
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function endTest(Test $test, float $time): void
    {
        if ($test instanceof BasilTestCaseInterface) {
            if ($this->exception instanceof Exception) {
                $this->write($this->generator->generate($this->exception));
            } else {
                $step = $this->stepFactory->create($test);
                $this->write($this->generator->generate($step));
            }
        }
    }

    public function printResult(TestResult $result): void
    {
        // @todo: Implement in #361
    }
}
