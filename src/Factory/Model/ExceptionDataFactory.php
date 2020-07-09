<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\ExceptionData\ExceptionDataInterface;
use webignition\BasilPhpUnitResultPrinter\FooModel\ExceptionData\InvalidLocatorExceptionData;
use webignition\BasilPhpUnitResultPrinter\FooModel\ExceptionData\UnknownExceptionData;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\SymfonyDomCrawlerNavigator\Exception\InvalidLocatorException;

class ExceptionDataFactory
{
    private NodeSourceFactory $nodeSourceFactory;

    public function __construct(NodeSourceFactory $nodeSourceFactory)
    {
        $this->nodeSourceFactory = $nodeSourceFactory;
    }

    public static function createFactory(): self
    {
        return new ExceptionDataFactory(
            NodeSourceFactory::createFactory()
        );
    }

    public function create(\Throwable $exception): ExceptionDataInterface
    {
        if ($exception instanceof InvalidLocatorException) {
            $identifier = $exception->getElementIdentifier();
            $nodeSource = $this->nodeSourceFactory->create((string) $identifier);

            if ($nodeSource instanceof NodeSource) {
                return new InvalidLocatorExceptionData(
                    $identifier->isCssSelector() ? Properties::TYPE_CSS : Properties::TYPE_XPATH,
                    $identifier->getLocator(),
                    $nodeSource
                );
            }
        }

        return new UnknownExceptionData(get_class($exception), $exception->getMessage());
    }
}
