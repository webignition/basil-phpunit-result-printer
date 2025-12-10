<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Functional;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResultPrinterExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[DataProvider('terminatedDataProvider')]
    public function testExceptionHandling(string $phpUnitTestPath): void
    {
        $phpunitCommand = './vendor/bin/phpunit -c phpunit.printer.xml ' . $phpUnitTestPath;

        $phpunitOutput = [];
        $exitCode = null;

        exec($phpunitCommand, $phpunitOutput, $exitCode);

        // Value of 2 taken from phpunit 9.6 PHPUnit\TextUI\TestRunner::EXCEPTION_EXIT
        self::assertSame(2, $exitCode);
    }

    /**
     * @return array<mixed>
     */
    public static function terminatedDataProvider(): array
    {
        $root = getcwd();

        return [
            'terminated, RuntimeException thrown during first step' => [
                'phpUnitTestPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInFirstStepTest.php',
            ],
            'terminated, RuntimeException thrown during second step' => [
                'phpUnitTestPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInSecondStepTest.php',
            ],
            'terminated, lastException set during setupBeforeClass' => [
                'phpUnitTestPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInSetupBeforeClassTest.php',
            ],
        ];
    }
}
