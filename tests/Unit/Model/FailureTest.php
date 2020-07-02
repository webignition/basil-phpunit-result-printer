<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilPhpUnitResultPrinter\Model\Failure;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class FailureTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(Failure $failure, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $failure->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'default' => [
                'failure' => new Failure('content'),
                'expectedRenderedString' => '<failure>content</failure>',
            ],
        ];
    }
}
