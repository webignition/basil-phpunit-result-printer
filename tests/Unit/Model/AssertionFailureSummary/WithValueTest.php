<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\WithValue;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class WithValueTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(WithValue $withValue, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $withValue->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'default' => [
                'withValue' => new WithValue('no indent'),
                'expectedRenderedString' => 'with value <comment>no indent</comment>',
            ],
        ];
    }
}
