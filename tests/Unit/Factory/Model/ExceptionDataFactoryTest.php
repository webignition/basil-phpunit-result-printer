<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory;

use Facebook\WebDriver\Exception\InvalidSelectorException;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\ExceptionDataFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\ExceptionDataInterface;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\InvalidLocatorExceptionData;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\UnknownExceptionData;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;
use webignition\DomElementIdentifier\ElementIdentifier;
use webignition\DomElementIdentifier\ElementIdentifierInterface;
use webignition\SymfonyDomCrawlerNavigator\Exception\InvalidLocatorException;

class ExceptionDataFactoryTest extends AbstractBaseTestCase
{
    private ExceptionDataFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = ExceptionDataFactory::createFactory();
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(\Throwable $exception, ExceptionDataInterface $expectedExceptionData): void
    {
        self::assertEquals($expectedExceptionData, $this->factory->create($exception));
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
    {
        $nodeSourceFactory = NodeSourceFactory::createFactory();

        $invalidLocatorNodeSource = $nodeSourceFactory->create('$"a[href=https://example.com]"');
        $invalidLocatorNodeSource = $invalidLocatorNodeSource ?? \Mockery::mock(NodeSource::class);

        $elementIdentifier = new ElementIdentifier('a[href=https://example.com]');

        $invalidElementIdentifier = \Mockery::mock(ElementIdentifierInterface::class);

        $invalidElementIdentifier
            ->shouldReceive('isCssSelector')
            ->andReturn(true)
        ;

        $invalidElementIdentifier
            ->shouldReceive('getLocator')
            ->andReturn('invalid')
        ;

        return [
            'invalid locator, css' => [
                'exception' => new InvalidLocatorException(
                    $elementIdentifier,
                    \Mockery::mock(InvalidSelectorException::class)
                ),
                'expectedExceptionData' => new InvalidLocatorExceptionData(
                    'css',
                    'a[href=https://example.com]',
                    $invalidLocatorNodeSource
                ),
            ],
            'invalid locator, identifier cannot be transformed into NodeSource' => [
                'exception' => new InvalidLocatorException(
                    $invalidElementIdentifier,
                    \Mockery::mock(InvalidSelectorException::class)
                ),
                'expectedExceptionData' => new UnknownExceptionData(
                    InvalidLocatorException::class,
                    'Invalid CSS selector locator invalid'
                ),
            ],
            'unknown' => [
                'exception' => new \RuntimeException('RuntimeException message'),
                'expectedExceptionData' => new UnknownExceptionData(
                    \RuntimeException::class,
                    'RuntimeException message'
                ),
            ],
        ];
    }
}
