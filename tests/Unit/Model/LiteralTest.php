<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilPhpUnitResultPrinter\Model\Literal;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class LiteralTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(Literal $literal, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $literal->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'default' => [
                'comment' => new Literal('content'),
                'expectedRenderedString' => 'content',
            ],
        ];
    }
}
