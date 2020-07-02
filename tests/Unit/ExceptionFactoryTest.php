<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit;

use Facebook\WebDriver\Exception\InvalidSelectorException;
use webignition\BasilPhpUnitResultPrinter\ExceptionFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Exception\InvalidLocator;
use webignition\BasilPhpUnitResultPrinter\Model\Exception\Unknown;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableInterface;
use webignition\DomElementIdentifier\ElementIdentifier;
use webignition\SymfonyDomCrawlerNavigator\Exception\InvalidLocatorException;

class ExceptionFactoryTest extends AbstractBaseTest
{
    private ExceptionFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new ExceptionFactory();
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(\Throwable $exception, RenderableInterface $expectedModel)
    {
        $this->assertEquals($expectedModel, $this->factory->create($exception));
    }

    public function createDataProvider(): array
    {
        $invalidLocatorException = new InvalidLocatorException(
            new ElementIdentifier('a[href=https://example.com]'),
            \Mockery::mock(InvalidSelectorException::class)
        );

        $logicException = new \LogicException('logic exception message');

        return [
            'InvalidLocatorException: CSS selector' => [
                'exception' => $invalidLocatorException,
                'expectedModel' => new InvalidLocator($invalidLocatorException),
            ],
            'unknown exception' => [
                'exception' => $logicException,
                'expectedModel' => new Unknown($logicException),
            ],
        ];
    }
}
