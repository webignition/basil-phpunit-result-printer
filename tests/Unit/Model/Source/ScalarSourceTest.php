<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Source;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\ScalarFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Scalar;
use webignition\BasilPhpUnitResultPrinter\Model\Source\ScalarSource;
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
                'body' => \Mockery::mock(Scalar::class),
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
        $scalarFactory = ScalarFactory::createFactory();

        $scalar = $scalarFactory->create('"literal"') ?? \Mockery::mock(Scalar::class);

        return [
            'scalar' => [
                'source' => new ScalarSource($scalar),
                'expectedData' => [
                    'type' => 'scalar',
                    'body' => $scalar->getData(),
                ],
            ],
        ];
    }
}