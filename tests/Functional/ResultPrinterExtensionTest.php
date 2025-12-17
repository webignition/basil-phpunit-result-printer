<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Functional;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResultPrinterExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[DataProvider('passingTestsDataProvider')]
    #[DataProvider('failingTestsDataProvider')]
    #[DataProvider('terminatedDataProvider')]
    public function testRun(string $testPath, int $expectedExitCode, string $expectedPhpunitOutput): void
    {
        $phpunitCommand = './vendor/bin/phpunit -c phpunit.printer.xml ' . $testPath;

        $phpunitOutput = [];
        $exitCode = null;

        exec($phpunitCommand, $phpunitOutput, $exitCode);

        self::assertSame($expectedExitCode, $exitCode);

        self::assertSame(
            $expectedPhpunitOutput,
            implode("\n", $phpunitOutput)
        );
    }

    /**
     * @return array<mixed>
     */
    public static function passingTestsDataProvider(): array
    {
        $root = getcwd();

        return [
            'passing' => [
                'testPath' => $root . '/tests/Fixtures/Tests/Passing01.php',
                'expectedExitCode' => 0,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Passed
                    PHPUnit\Event\Test\Finished
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Passed
                    PHPUnit\Event\Test\Finished
                    EOD,
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function failingTestsDataProvider(): array
    {
        $root = getcwd();

        return [
            'failing' => [
                'testPath' => $root . '/tests/Fixtures/Tests/Failing01.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Failed
                    PHPUnit\Event\Test\Finished
                    EOD,
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function terminatedDataProvider(): array
    {
        $root = getcwd();

        return [
            'terminated, RuntimeException thrown during first step' => [
                'testPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInFirstStepTest.php',
                'expectedExitCode' => 2,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Errored
                    PHPUnit\Event\Test\Finished
                    EOD,
            ],
            'terminated, RuntimeException thrown during second step' => [
                'testPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInSecondStepTest.php',
                'expectedExitCode' => 2,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Passed
                    PHPUnit\Event\Test\Finished
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Errored
                    PHPUnit\Event\Test\Finished
                    EOD,
            ],
            'terminated, lastException set during setupBeforeClass' => [
                'testPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInSetupBeforeClassTest.php',
                'expectedExitCode' => 2,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\BeforeFirstTestMethodErrored
                    EOD,
            ],
        ];
    }
}
