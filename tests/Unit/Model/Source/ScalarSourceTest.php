<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Source;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\ScalarFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Scalar;
use webignition\BasilPhpUnitResultPrinter\Model\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;
use webignition\ObjectReflector\ObjectReflector;

class ScalarSourceTest extends AbstractBaseTestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(Scalar $body): void
    {
        $node = new ScalarSource($body);

        self::assertSame($body, ObjectReflector::getProperty($node, 'body'));
    }

    /**
     * @return array<mixed>
     */
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
     * @param array<mixed> $expectedData
     */
    public function testGetData(ScalarSource $source, array $expectedData): void
    {
        self::assertSame($expectedData, $source->getData());
    }

    /**
     * @return array<mixed>
     */
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
