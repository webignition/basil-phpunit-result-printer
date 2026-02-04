<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnit\TextUI\Output\DefaultPrinter;
use webignition\BasilModels\Model\Statement\StatementFactory;
use webignition\BasilPhpUnitResultPrinter\AssertionFailure\AssertionFailureFactory;
use webignition\BasilPhpUnitResultPrinter\AssertionFailure\ExceptionFactory;
use webignition\BasilPhpUnitResultPrinter\ExpectationFailure\ExpectationFailureFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\StepFactory;
use webignition\BasilPhpUnitResultPrinter\Generator\YamlGenerator;
use webignition\BasilPhpUnitResultPrinter\StepDataExtractor\DataSetExtractor;
use webignition\BasilPhpUnitResultPrinter\StepDataExtractor\NameExtractor;
use webignition\BasilPhpUnitResultPrinter\StepDataExtractor\StatementCollectionExtractor;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\BeforeFirstTestMethodErroredSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\ErroredSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\FailedSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\FinishedSubscriber;
use webignition\BasilPhpUnitResultPrinter\Subscriber\Test\PassedSubscriber;

class ResultPrinterExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        if ($configuration->noOutput()) {
            return;
        }

        $facade->replaceOutput();

        $printer = DefaultPrinter::standardOutput();
        $state = new State();
        $yamlGenerator = new YamlGenerator();
        $statementFactory = StatementFactory::createFactory();

        $facade->registerSubscribers(
            new BeforeFirstTestMethodErroredSubscriber($printer, $yamlGenerator, (string) getcwd()),
            new ErroredSubscriber($state),
            new FailedSubscriber(
                $state,
                new StatementMessageParser(),
                new AssertionFailureFactory($statementFactory, new ExceptionFactory()),
                new ExpectationFailureFactory($statementFactory),
            ),
            new PassedSubscriber($state),
            new FinishedSubscriber(
                $printer,
                $state,
                new NameExtractor(),
                new StatementCollectionExtractor($statementFactory),
                new DataSetExtractor(),
                StepFactory::createFactory(),
                $yamlGenerator,
            ),
        );
    }
}
