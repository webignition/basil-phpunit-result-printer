<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\StatementLine;

use webignition\BasilModels\Action\Action;
use webignition\BasilPhpUnitResultPrinter\Model\StatementLine\LabelledStatement;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class LabelledStatementTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(LabelledStatement $labelledStatement, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $labelledStatement->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'default' => [
                'labelledStatement' => new LabelledStatement(
                    'label',
                    new Action(
                        'click $".selector"',
                        'click',
                        '$".selector'
                    )
                ),
                'expectedRenderedString' => '<comment>> label:</comment> click $".selector"',
            ],
        ];
    }
}
