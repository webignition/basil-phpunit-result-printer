<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\DataSet;

use webignition\BasilPhpUnitResultPrinter\Model\DataSet\KeyValue;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class KeyValueTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(KeyValue $keyValue, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $keyValue->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'default' => [
                'keyValue' => new KeyValue('key', 'value'),
                'expectedRenderedString' => '$key: <comment>value</comment>',
            ],
        ];
    }
}
