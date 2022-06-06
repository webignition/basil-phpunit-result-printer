<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BasilModels\Model\Test\Configuration;

class ThrowsRuntimeExceptionInFirstStepTest extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::setBasilTestPath('/path/to/runtime-exception-on-first-step-test.yml');
        self::setBasilTestConfiguration(new Configuration('chrome', 'http://example.com'));

        parent::setUpBeforeClass();
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
