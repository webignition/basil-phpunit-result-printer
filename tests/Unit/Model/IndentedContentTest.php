<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilPhpUnitResultPrinter\Model\IndentedContent;
use webignition\BasilPhpUnitResultPrinter\Model\Literal;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class IndentedContentTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(IndentedContent $indentedContent, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $indentedContent->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'literal, single line' => [
                'indentedContent' => new IndentedContent(new Literal('content')),
                'expectedRenderedString' => '  content',
            ],
            'literal, multi-line' => [
                'indentedContent' => new IndentedContent(new Literal(
                    'line1' . "\n" .
                    'line2' . "\n" .
                    "\n" .
                    'line4'
                )),
                'expectedRenderedString' =>
                    '  line1' . "\n" .
                    '  line2' . "\n" .
                    "\n" .
                    '  line4',
            ],
        ];
    }
}
