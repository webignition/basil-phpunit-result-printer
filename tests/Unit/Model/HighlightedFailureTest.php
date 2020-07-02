<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilPhpUnitResultPrinter\Model\HighlightedFailure;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class HighlightedFailureTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(HighlightedFailure $failure, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $failure->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'default' => [
                'failure' => new HighlightedFailure('content'),
                'expectedRenderedString' => '<highlighted-failure>content</highlighted-failure>',
            ],
        ];
    }
}
