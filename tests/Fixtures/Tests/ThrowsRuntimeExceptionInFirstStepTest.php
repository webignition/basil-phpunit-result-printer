<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

class ThrowsRuntimeExceptionInFirstStepTest extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::setBasilTestPath('/path/to/runtime-exception-on-first-step-test.yml');
    }

    public function testStep1()
    {
        $this->setBasilStepName('step one');
        throw new \RuntimeException('Exception thrown in first step', 123);
    }

    public function testStep2()
    {
        $this->setBasilStepName('step two');
        self::assertTrue(true);
    }
}
