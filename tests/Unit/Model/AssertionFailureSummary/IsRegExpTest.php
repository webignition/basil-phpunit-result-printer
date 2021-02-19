<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\ScalarSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\IsRegExp;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Model\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\Model\Source\SourceInterface;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class IsRegExpTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $value, SourceInterface $source): void
    {
        $summary = new IsRegExp($value, $source);

        self::assertSame($value, ObjectReflector::getProperty($summary, 'value'));
        self::assertSame($source, ObjectReflector::getProperty($summary, 'source'));
    }

    /**
     * @return array[]
     */
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
    public function testGetData(IsRegExp $summary, array $expectedData): void
    {
        self::assertSame($expectedData, $summary->getData());
    }

    /**
     * @return array[]
     */
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
