<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\ScalarToScalarComparisonSummary;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class ScalarToScalarComparisonSummaryTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(ScalarToScalarComparisonSummary $summary, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $summary->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'is' => [
                'summary' => new ScalarToScalarComparisonSummary(
                    'is',
                    'expected',
                    'actual'
                ),
                'expectedRenderedString' => '* <comment>actual</comment> is not equal to <comment>expected</comment>',
            ],
            'is-not' => [
                'summary' => new ScalarToScalarComparisonSummary(
                    'is-not',
                    'expected',
                    'expected'
                ),
                'expectedRenderedString' => '* <comment>expected</comment> is equal to <comment>expected</comment>',
            ],
            'includes' => [
                'summary' => new ScalarToScalarComparisonSummary(
                    'includes',
                    'expected',
                    'actual'
                ),
                'expectedRenderedString' => '* <comment>actual</comment> does not include <comment>expected</comment>',
            ],
            'excludes' => [
                'summary' => new ScalarToScalarComparisonSummary(
                    'excludes',
                    'actual',
                    'actual'
                ),
                'expectedRenderedString' => '* <comment>actual</comment> does not exclude <comment>actual</comment>',
            ],
            'matches' => [
                'summary' => new ScalarToScalarComparisonSummary(
                    'matches',
                    '/expected/',
                    'actual'
                ),
                'expectedRenderedString' =>
                    '* <comment>actual</comment> does not match regular expression <comment>/expected/</comment>',
            ],
        ];
    }
}
