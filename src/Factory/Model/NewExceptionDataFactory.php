<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model;

use webignition\BasilPhpUnitResultPrinter\AssertionFailureException;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\ExceptionDataInterface;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\InvalidLocatorExceptionData;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\UnknownExceptionData;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;

class NewExceptionDataFactory
{
    public function __construct(
        private NodeSourceFactory $nodeSourceFactory
    ) {}

    public static function createFactory(): self
    {
        return new NewExceptionDataFactory(
            NodeSourceFactory::createFactory()
        );
    }

    public function create(AssertionFailureException $exception): ?ExceptionDataInterface
    {
        return new UnknownExceptionData($exception->class, $exception->message);
    }

    public function createForInvalidLocator(string $locator, string $type): ?ExceptionDataInterface
    {
        $nodeSource = $this->nodeSourceFactory->create($locator);

        if ($nodeSource instanceof NodeSource) {
            return new InvalidLocatorExceptionData(
                'css' === $type ? Properties::TYPE_CSS : Properties::TYPE_XPATH,
                $locator,
                $nodeSource
            );
        }

        return null;
    }
}
