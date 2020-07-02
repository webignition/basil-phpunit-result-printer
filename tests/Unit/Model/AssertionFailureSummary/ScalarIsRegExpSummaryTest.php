<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\ScalarIsRegExpSummary;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class ScalarIsRegExpSummaryTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(ScalarIsRegExpSummary $scalarIsRegExpSummary, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $scalarIsRegExpSummary->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'default' => [
                'scalarIsRegExpSummary' => new ScalarIsRegExpSummary('/invalid/'),
                'expectedRenderedString' => '* <comment>/invalid/</comment> is not a valid regular expression',
            ],
        ];
    }
}
