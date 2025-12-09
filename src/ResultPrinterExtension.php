<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\ErroredSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\FailedSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\FinishedSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\PassedSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\PreparedSubscriber;

class ResultPrinterExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        if ($configuration->noOutput()) {
            return;
        }

        $facade->replaceOutput();

        $facade->registerSubscribers(
            new PreparedSubscriber(),
            new FinishedSubscriber(),
            new ErroredSubscriber(),
            new FailedSubscriber(),
            new PassedSubscriber(),
        );
    }
}
