<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class StatusTest extends AbstractBaseTest
{
    /**
     * @dataProvider toStringDataProvider
     */
    public function testToString(Status $status, string $expectedString): void
    {
        self::assertSame($expectedString, (string) $status);
    }

    /**
     * @return array<mixed>
     */
    public function toStringDataProvider(): array
    {
        return [
            'passed' => [
                'status' => new Status(Status::STATUS_PASSED),
                'expectedString' => 'passed',
            ],
            'failed' => [
                'status' => new Status(Status::STATUS_FAILED),
                'expectedString' => 'failed',
            ],
            'unknown' => [
                'status' => new Status(-1),
                'expectedString' => 'unknown',
            ],
        ];
    }
}
