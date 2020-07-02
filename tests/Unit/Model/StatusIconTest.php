<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\Model\StatusIcon;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class StatusIconTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(StatusIcon $statusIcon, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $statusIcon->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'success' => [
                'statusIcon' => new StatusIcon(Status::SUCCESS),
                'expectedRenderedString' => '<icon-success />',
            ],
            'failure' => [
                'statusIcon' => new StatusIcon(Status::FAILURE),
                'expectedRenderedString' => '<icon-failure />',
            ],
            'unknown' => [
                'statusIcon' => new StatusIcon(-1),
                'expectedRenderedString' => '<icon-unknown />',
            ],
        ];
    }
}
