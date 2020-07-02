<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\DataSet;

use webignition\BasilPhpUnitResultPrinter\Model\DataSet\KeyValue;
use webignition\BasilPhpUnitResultPrinter\Model\DataSet\KeyValueCollection;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class KeyValueCollectionTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(KeyValueCollection $collection, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $collection->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'empty' => [
                'collection' => new KeyValueCollection([]),
                'expectedRenderedString' => '',
            ],
            'non-empty' => [
                'collection' => new KeyValueCollection([
                    new KeyValue('key1', 'value1'),
                    new KeyValue('key2', 'value2'),
                ]),
                'expectedRenderedString' =>
                    '$key1: <comment>value1</comment>' . "\n" .
                    '$key2: <comment>value2</comment>',
            ],
        ];
    }
}
