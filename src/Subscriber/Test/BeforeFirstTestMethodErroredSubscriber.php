<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\BeforeFirstTestMethodErrored;
use PHPUnit\Event\Test\BeforeFirstTestMethodErroredSubscriber as BeforeFirstTestMethodErroredSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\Generator\GeneratorInterface;
use webignition\BasilRunnerDocuments\Exception;
use webignition\BasilRunnerDocuments\StackTrace;
use webignition\BasilRunnerDocuments\StackTraceLine;

readonly class BeforeFirstTestMethodErroredSubscriber implements BeforeFirstTestMethodErroredSubscriberInterface
{
    public function __construct(
        private Printer $printer,
        private GeneratorInterface $generator,
    ) {}

    public function notify(BeforeFirstTestMethodErrored $event): void
    {
        $exceptionDocument = new Exception(
            $event->throwable()->className(),
            $event->throwable()->message(),
            0,
        );

        $exceptionDocument = $exceptionDocument->withTrace(
            $this->createExceptionDocumentStackTrace($event->throwable()->stackTrace())
        );

        $this->printer->print($this->generator->generate($exceptionDocument->getData()));
    }

    private function createExceptionDocumentStackTrace(string $serializedTrace): StackTrace
    {
        $traceLines = [];

        $lines = explode("\n", $serializedTrace);

        foreach ($lines as $line) {
            if ('' === $line) {
                continue;
            }

            $parts = explode(':', $line);
            if (2 !== count($parts)) {
                continue;
            }

            $path = $parts[0];
            if ('' === $path) {
                continue;
            }

            $lineNumber = $parts[1];
            if (!ctype_digit($lineNumber)) {
                continue;
            }

            $lineNumber = (int) $lineNumber;
            if ($lineNumber < 1) {
                continue;
            }

            $traceLines[] = new StackTraceLine($path, $lineNumber);
        }

        return new StackTrace($traceLines);
    }
}
