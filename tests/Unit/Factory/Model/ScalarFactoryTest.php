<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\ScalarFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Scalar;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;

class ScalarFactoryTest extends AbstractBaseTestCase
{
    private ScalarFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = ScalarFactory::createFactory();
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $source, ?Scalar $expectedScalar): void
    {
        self::assertEquals($expectedScalar, $this->factory->create($source));
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        return [
            'empty' => [
                'source' => '',
                'expectedScalar' => null,
            ],
            'browser property: invalid' => [
                'source' => '$browser.invalid',
                'expectedScalar' => null,
            ],
            'browser property: valid' => [
                'source' => '$browser.size',
                'expectedScalar' => new Scalar(
                    Scalar::TYPE_BROWSER_PROPERTY,
                    '$browser.size'
                ),
            ],
            'data parameter: invalid' => [
                'source' => '$datakey',
                'expectedScalar' => null,
            ],
            'data parameter: valid' => [
                'source' => '$data.key',
                'expectedScalar' => new Scalar(
                    Scalar::TYPE_DATA_PARAMETER,
                    '$data.key'
                ),
            ],
            'environment parameter: invalid' => [
                'source' => '$envkey',
                'expectedScalar' => null,
            ],
            'environment parameter: valid' => [
                'source' => '$env.key',
                'expectedScalar' => new Scalar(
                    Scalar::TYPE_ENVIRONMENT_PARAMETER,
                    '$env.key'
                ),
            ],
            'literal: invalid' => [
                'source' => 'literal',
                'expectedScalar' => null,
            ],
            'literal: valid' => [
                'source' => '"literal"',
                'expectedScalar' => new Scalar(
                    Scalar::TYPE_LITERAL,
                    '"literal"'
                ),
            ],
            'page property: invalid' => [
                'source' => '$pageproperty',
                'expectedScalar' => null,
            ],
            'page property: valid' => [
                'source' => '$page.url',
                'expectedScalar' => new Scalar(
                    Scalar::TYPE_PAGE_PROPERTY,
                    '$page.url'
                ),
            ],
        ];
    }
}
