<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\DataSet;

use webignition\BasilPhpUnitResultPrinter\Model\DataSet\Key;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class KeyTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(Key $key, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $key->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'default' => [
                'key' => new Key('name'),
                'expectedRenderedString' => '$name',
            ],
        ];
    }
}
