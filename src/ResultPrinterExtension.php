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
use webignition\BasilPhpUnitResultPrinter\ExpectedActualValuesParser\HasComparisonFailureHandler;
use webignition\BasilPhpUnitResultPrinter\ExpectedActualValuesParser\Parser;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\BeforeFirstTestMethodErroredSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\ErroredSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\FailedSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\FinishedSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\PassedSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\PreparedSubscriber;

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
            new PreparedSubscriber($this->printer),
            new FinishedSubscriber(
                $this->printer,
                $this->state,
                new TestMetaDataExtractor(
                    StatementFactory::createFactory(),
                ),
                new TestDataExtractor(),
            ),
            new ErroredSubscriber($this->printer, $this->state),
            new FailedSubscriber(
                $this->printer,
                $this->state,
                new StatementMessageParser(),
                new FailedActionExtractor(
                    StatementFactory::createFactory(),
                    new FailedActionExceptionExtractor(),
                ),
                new FailedAssertionExtractor(
                    StatementFactory::createFactory(),
                ),
                new Parser(
                    [
                        new HasComparisonFailureHandler(),
                    ]
                ),
            ),
            new PassedSubscriber($this->printer, $this->state),
            new BeforeFirstTestMethodErroredSubscriber($this->printer),
        );
    }
}
