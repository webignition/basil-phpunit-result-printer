<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\ScalarSourceFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\IsRegExp;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\SourceInterface;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class IsRegExpTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $value, SourceInterface $source)
    {
        $summary = new IsRegExp($value, $source);

        self::assertSame($value, ObjectReflector::getProperty($summary, 'value'));
        self::assertSame($source, ObjectReflector::getProperty($summary, 'source'));
    }

    public function createDataProvider(): array
    {
        return [
            'node' => [
                'value' => 'not a regexp from node',
                'source' => \Mockery::mock(NodeSource::class),
            ],
            'scalar' => [
                'value' => 'not a regexp from scalar',
                'source' => \Mockery::mock(ScalarSource::class),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param IsRegExp $summary
     * @param array<mixed> $expectedData
     */
    public function testGetData(IsRegExp $summary, array $expectedData)
    {
        self::assertSame($expectedData, $summary->getData());
    }

    public function getDataDataProvider(): array
    {
        $nodeSourceFactory = NodeSourceFactory::createFactory();
        $scalarSourceFactory = ScalarSourceFactory::createFactory();

        $nodeSource = $nodeSourceFactory->create('$".selector"') ?? \Mockery::mock(NodeSource::class);
        $scalarSource = $scalarSourceFactory->create('"not regexp scalar"') ?? \Mockery::mock(ScalarSource::class);

        return [
            'node' => [
                'summary' => new IsRegExp('not regexp node', $nodeSource),
                'expectedData' => [
                    'operator' => 'is-regexp',
                    'value' => 'not regexp node',
                    'source' => $nodeSource->getData(),
                ],
            ],
            'scalar' => [
                'summary' => new IsRegExp('not regexp scalar', $scalarSource),
                'expectedData' => [
                    'operator' => 'is-regexp',
                    'value' => 'not regexp scalar',
                    'source' => $scalarSource->getData(),
                ],
            ],
        ];
    }
}
