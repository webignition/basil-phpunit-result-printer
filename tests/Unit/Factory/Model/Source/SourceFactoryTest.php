<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Factory\Model\Source;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\ScalarSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\SourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Source\SourceInterface;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;

class SourceFactoryTest extends AbstractBaseTestCase
{
    private SourceFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = SourceFactory::createFactory();
    }

    #[DataProvider('createDataProvider')]
    public function testCreate(string $source, ?SourceInterface $expected): void
    {
        self::assertEquals($expected, $this->factory->create($source));
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        $nodeSourceFactory = NodeSourceFactory::createFactory();
        $scalarSourceFactory = ScalarSourceFactory::createFactory();

        return [
            'empty' => [
                'source' => '',
                'expected' => null,
            ],
            'node' => [
                'source' => '$".selector"',
                'expected' => $nodeSourceFactory->create('$".selector"'),
            ],
            'scalar' => [
                'source' => '"literal"',
                'expected' => $scalarSourceFactory->create('"literal"'),
            ],
        ];
    }
}
