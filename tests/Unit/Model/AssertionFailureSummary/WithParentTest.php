<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\WithParent;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class WithParentTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(WithParent $withParent, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $withParent->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'default' => [
                'withParent' => new WithParent(),
                'expectedRenderedString' => 'with parent:',
            ],
        ];
    }
}
