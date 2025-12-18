<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnit\TextUI\Output\DefaultPrinter;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\BeforeFirstTestMethodErroredSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\ErroredSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\FailedSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\FinishedSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\PassedSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\PreparedSubscriber;

readonly class ResultPrinterExtension implements Extension
{
    private Printer $printer;

    public function __construct()
    {
        $this->printer = DefaultPrinter::standardOutput();
    }

    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        if ($configuration->noOutput()) {
            return;
        }

        $facade->replaceOutput();

        $facade->registerSubscribers(
            new PreparedSubscriber($this->printer),
            new FinishedSubscriber($this->printer, new TestDataExtractor()),
            new ErroredSubscriber($this->printer),
            new FailedSubscriber($this->printer, new TestDataExtractor()),
            new PassedSubscriber($this->printer),
            new BeforeFirstTestMethodErroredSubscriber($this->printer),
        );
    }
}
