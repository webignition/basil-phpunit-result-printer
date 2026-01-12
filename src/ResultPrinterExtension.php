<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnit\TextUI\Output\DefaultPrinter;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilModels\Model\StatementFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\NewStepFactory;
use webignition\BasilPhpUnitResultPrinter\Generator\YamlGenerator;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\BeforeFirstTestMethodErroredSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\ErroredSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\FailedSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\FinishedSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\PassedSubscriber;

class ResultPrinterExtension implements Extension
{
    private Printer $printer;
    private State $state;

    public function __construct()
    {
        $this->printer = DefaultPrinter::standardOutput();
        $this->state = new State();
    }

    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        if ($configuration->noOutput()) {
            return;
        }

        $facade->replaceOutput();

        $facade->registerSubscribers(
            new FinishedSubscriber(
                $this->printer,
                $this->state,
                new TestMetaDataExtractor(
                    StatementFactory::createFactory(),
                ),
                new TestDataExtractor(),
                NewStepFactory::createFactory(),
                new YamlGenerator(),
            ),
            new ErroredSubscriber($this->state),
            new FailedSubscriber(
                $this->state,
                new StatementMessageParser(),
                new AssertionFailureExtractor(
                    StatementFactory::createFactory(),
                    new AssertionFailureExceptionExtractor(),
                ),
                new ExpectationFailureExtractor(
                    StatementFactory::createFactory(),
                ),
            ),
            new PassedSubscriber($this->state),
            new BeforeFirstTestMethodErroredSubscriber($this->printer),
        );
    }
}
