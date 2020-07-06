<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\Source;

use webignition\BasilPhpUnitResultPrinter\FooModel\Scalar;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class ScalarSourceTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(Scalar $body)
    {
        $node = new ScalarSource($body);

        self::assertSame($body, ObjectReflector::getProperty($node, 'body'));
    }

    public function createDataProvider(): array
    {
        return [
            'scalar' => [
                'body' => new Scalar(
                    Scalar::TYPE_LITERAL,
                    'literal'
                ),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param ScalarSource $source
     * @param array<mixed> $expectedData
     */
    public function testGetData(ScalarSource $source, array $expectedData)
    {
        self::assertSame($expectedData, $source->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'scalar' => [
                'source' => new ScalarSource(
                    new Scalar(
                        Scalar::TYPE_LITERAL,
                        'literal'
                    )
                ),
                'expectedData' => [
                    'type' => 'scalar',
                    'body' => [
                        'type' => Scalar::TYPE_LITERAL,
                        'value' => 'literal',
                    ],
                ],
            ],
        ];
    }
}
