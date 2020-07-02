<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilPhpUnitResultPrinter\Model\Success;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class SuccessTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(Success $success, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $success->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'default' => [
                'success' => new Success('content'),
                'expectedRenderedString' => '<success>content</success>',
            ],
        ];
    }
}
