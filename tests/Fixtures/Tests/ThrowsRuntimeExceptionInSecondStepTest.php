<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

class ThrowsRuntimeExceptionInSecondStepTest extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::setBasilTestPath('/path/to/runtime-exception-on-second-step-test.yml');

        parent::setUpBeforeClass();
    }

    public function testStep1()
    {
        $this->setBasilStepName('step one');
        self::assertTrue(true);
    }

    public function testStep2()
    {
        $this->setBasilStepName('step two');

        throw new \RuntimeException('Exception thrown in first step', 123);
    }
}
