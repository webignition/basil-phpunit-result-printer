<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilPhpUnitResultPrinter\Model\Scalar;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class ScalarTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $type, string $value): void
    {
        $scalar = new Scalar($type, $value);

        $this->assertSame($type, ObjectReflector::getProperty($scalar, 'type'));
        $this->assertSame($value, ObjectReflector::getProperty($scalar, 'value'));
    }

    /**
     * @return array[]
     */
    public function createDataProvider(): array
    {
        return [
            'browser property' => [
                'type' => Scalar::TYPE_BROWSER_PROPERTY,
                'value' => '$browser.size',
            ],
            'data parameter' => [
                'type' => Scalar::TYPE_DATA_PARAMETER,
                'value' => '$data.key',
            ],
            'environment parameter' => [
                'type' => Scalar::TYPE_ENVIRONMENT_PARAMETER,
                'value' => '$env.KEY',
            ],
            'literal' => [
                'type' => Scalar::TYPE_LITERAL,
                'value' => 'literal',
            ],
            'page property' => [
                'type' => Scalar::TYPE_PAGE_PROPERTY,
                'value' => '$page.url',
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param array<mixed> $expectedData
     */
    public function testGetData(Scalar $scalar, array $expectedData): void
    {
        self::assertSame($expectedData, $scalar->getData());
    }

    /**
     * @return array[]
     */
    public function getDataDataProvider(): array
    {
        return [
            'browser property' => [
                'scalar' => new Scalar(
                    Scalar::TYPE_BROWSER_PROPERTY,
                    '$browser.size'
                ),
                'expectedData' => [
                    'type' => Scalar::TYPE_BROWSER_PROPERTY,
                    'value' => '$browser.size',
                ],
            ],
            'data parameter' => [
                'scalar' => new Scalar(
                    Scalar::TYPE_DATA_PARAMETER,
                    '$data.key'
                ),
                'expectedData' => [
                    'type' => Scalar::TYPE_DATA_PARAMETER,
                    'value' => '$data.key',
                ],
                ],
            'environment parameter' => [
                'scalar' => new Scalar(
                    Scalar::TYPE_ENVIRONMENT_PARAMETER,
                    '$env.KEY'
                ),
                'expectedData' => [
                    'type' => Scalar::TYPE_ENVIRONMENT_PARAMETER,
                    'value' => '$env.KEY',
                ],
                ],
            'literal' => [
                'scalar' => new Scalar(
                    Scalar::TYPE_LITERAL,
                    'literal'
                ),
                'expectedData' => [
                    'type' => Scalar::TYPE_LITERAL,
                    'value' => 'literal',
                ],
            ],
            'page property' => [
                'scalar' => new Scalar(
                    Scalar::TYPE_PAGE_PROPERTY,
                    '$page.url'
                ),
                'expectedData' => [
                    'type' => Scalar::TYPE_PAGE_PROPERTY,
                    'value' => '$page.url',
                ],
            ],
        ];
    }
}
