<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Factory\Model\Source;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\ScalarFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\ScalarSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Scalar;
use webignition\BasilPhpUnitResultPrinter\Model\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;

class ScalarSourceFactoryTest extends AbstractBaseTestCase
{
    private ScalarSourceFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = ScalarSourceFactory::createFactory();
    }

    #[DataProvider('createDataProvider')]
    public function testCreate(string $source, ?ScalarSource $expected): void
    {
        self::assertEquals($expected, $this->factory->create($source));
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        $scalarFactory = ScalarFactory::createFactory();

        return [
            'empty' => [
                'source' => '',
                'expected' => null,
            ],
            'non-empty' => [
                'source' => '"literal"',
                'expected' => new ScalarSource(
                    $scalarFactory->create('"literal"') ?? \Mockery::mock(Scalar::class)
                ),
            ],
        ];
    }
}
