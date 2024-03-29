<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory\Source;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\ScalarFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\ScalarSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Scalar;
use webignition\BasilPhpUnitResultPrinter\Model\Source\ScalarSource;
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
    public function testCreate(string $source, ?ScalarSource $expectedNodeSource): void
    {
        self::assertEquals($expectedNodeSource, $this->factory->create($source));
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
    {
        $scalarFactory = ScalarFactory::createFactory();

        return [
            'empty' => [
                'source' => '',
                'expectedScalarSource' => null,
            ],
            'non-empty' => [
                'source' => '"literal"',
                'expectedScalarSource' => new ScalarSource(
                    $scalarFactory->create('"literal"') ?? \Mockery::mock(Scalar::class)
                ),
            ],
        ];
    }
}
