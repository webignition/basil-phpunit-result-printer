<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory\Source;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\ScalarSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\SourceFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\SourceInterface;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class SourceFactoryTest extends AbstractBaseTest
{
    private SourceFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = SourceFactory::createFactory();
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $source, ?SourceInterface $expectedNodeSource)
    {
        self::assertEquals($expectedNodeSource, $this->factory->create($source));
    }

    public function createDataProvider(): array
    {
        $nodeSourceFactory = NodeSourceFactory::createFactory();
        $scalarSourceFactory = ScalarSourceFactory::createFactory();

        return [
            'empty' => [
                'source' => '',
                'expectedSource' => null,
            ],
            'node' => [
                'source' => '$".selector"',
                'expectedSource' => $nodeSourceFactory->create('$".selector"'),
            ],
            'scalar' => [
                'source' => '"literal"',
                'expectedSource' => $scalarSourceFactory->create('"literal"'),
            ],
        ];
    }
}
