<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory\Source;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\ScalarSourceFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\Scalar;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class ScalarSourceFactoryTest extends AbstractBaseTest
{
    private ScalarSourceFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = ScalarSourceFactory::createFactory();
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $source, ?ScalarSource $expectedNodeSource)
    {
        self::assertEquals($expectedNodeSource, $this->factory->create($source));
    }

    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'source' => '',
                'expectedScalarSource' => null,
            ],
            'browser property' => [
                'source' => '$browser.size',
                'expectedScalarSource' => new ScalarSource(
                    new Scalar(Scalar::TYPE_BROWSER_PROPERTY, '$browser.size')
                ),
            ],
            'data parameter' => [
                'source' => '$data.key',
                'expectedScalarSource' => new ScalarSource(
                    new Scalar(Scalar::TYPE_DATA_PARAMETER, '$data.key')
                ),
            ],
            'environment parameter' => [
                'source' => '$env.key',
                'expectedScalarSource' => new ScalarSource(
                    new Scalar(Scalar::TYPE_ENVIRONMENT_PARAMETER, '$env.key')
                ),
            ],
            'literal' => [
                'source' => '"literal"',
                'expectedScalarSource' => new ScalarSource(
                    new Scalar(Scalar::TYPE_LITERAL, '"literal"')
                ),
            ],
            'page property' => [
                'source' => '$page.url',
                'expectedScalarSource' => new ScalarSource(
                    new Scalar(Scalar::TYPE_PAGE_PROPERTY, '$page.url')
                ),
            ],
        ];
    }
}
