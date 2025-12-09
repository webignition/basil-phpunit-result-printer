<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\SourceFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\ValueFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Model\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\Model\Value;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;

class ValueFactoryTest extends AbstractBaseTestCase
{
    private ValueFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = ValueFactory::createFactory();
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $value, string $sourceString, ?Value $expectedValue): void
    {
        self::assertEquals($expectedValue, $this->factory->create($value, $sourceString));
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
    {
        $sourceFactory = SourceFactory::createFactory();

        return [
            'node' => [
                'value' => 'value',
                'sourceString' => '$".selector"',
                'expectedValue' => new Value(
                    'value',
                    $sourceFactory->create('$".selector"') ?? \Mockery::mock(NodeSource::class)
                ),
            ],
            'scalar' => [
                'value' => 'value',
                'sourceString' => '"value"',
                'expectedValue' => new Value(
                    'value',
                    $sourceFactory->create('"value"') ?? \Mockery::mock(ScalarSource::class)
                ),
            ],
            'invalid' => [
                'value' => 'value',
                'sourceString' => 'invalid',
                'expectedValue' => null,
            ],
        ];
    }
}
