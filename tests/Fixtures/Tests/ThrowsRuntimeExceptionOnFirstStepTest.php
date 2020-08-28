<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

class ThrowsRuntimeExceptionOnFirstStepTest extends BasilTestCase
{
    public static function getBasilTestPath(): string
    {
        return '/path/to/runtime-exception-on-first-step-test.yml';
    }

    public function testWhichThrowsAnException()
    {
        $this->setBasilStepName('step name');
        throw new \RuntimeException('Exception message', 123);
    }
}
