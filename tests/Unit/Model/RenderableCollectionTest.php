<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilPhpUnitResultPrinter\Model\Literal;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableCollection;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class RenderableCollectionTest extends AbstractBaseTest
{
    public function testAppend()
    {
        $collection = new RenderableCollection([]);
        self::assertEquals([], ObjectReflector::getProperty($collection, 'items'));

        $item1 = new Literal('item1');
        $collection = $collection->append($item1);
        self::assertEquals([$item1], ObjectReflector::getProperty($collection, 'items'));

        $item2 = new Literal('item2');
        $collection = $collection->append($item2);
        self::assertEquals([$item1, $item2], ObjectReflector::getProperty($collection, 'items'));
    }

    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(RenderableCollection $collection, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $collection->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'single literal' => [
                'collection' => new RenderableCollection([
                    new Literal('content'),
                ]),
                'expectedRenderedString' => 'content',
            ],
            'multiple literals' => [
                'collection' => new RenderableCollection([
                    new Literal('line1'),
                    new Literal('line2'),
                    new Literal('line3'),
                ]),
                'expectedRenderedString' =>
                    'line1' . "\n" .
                    'line2' . "\n" .
                    'line3',
            ],
        ];
    }
}
