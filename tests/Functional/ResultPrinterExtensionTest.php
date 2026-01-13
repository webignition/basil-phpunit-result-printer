<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Functional;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use webignition\BasilPhpUnitResultPrinter\Tests\Services\FixtureLoader;

class ResultPrinterExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[DataProvider('passingTestsDataProvider')]
    #[DataProvider('failingTestsDataProvider')]
    #[DataProvider('terminatedDataProvider')]
    public function testRun(string $testPath, int $expectedExitCode, string $expectedOutput): void
    {
        $phpunitCommand = './vendor/bin/phpunit -c phpunit.printer.xml ' . $testPath;

        $phpunitOutput = [];
        $exitCode = null;

        exec($phpunitCommand, $phpunitOutput, $exitCode);

        self::assertSame($expectedExitCode, $exitCode);

        self::assertSame(
            trim($expectedOutput),
            implode("\n", $phpunitOutput)
        );
    }

    /**
     * @return array<mixed>
     */
    public static function failingTestsDataProvider(): array
    {
        $root = getcwd();

        return [
            'failing regular assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingRegularAssertTrueAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-exists-assertion-as-third-statement.yaml'
                ),
            ],
            'failing derived assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingDerivedAssertTrueAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-derived-exists-assertion.yaml'),
            ],
            'failing action' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingAction.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-click-action.yaml'),
            ],
            'failing "string contains string" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingStringContainsStringAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-includes-assertion.yaml'),
            ],
            'failing "string not contains string" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingStringNotContainsStringAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-excludes-assertion.yaml'),
            ],
            'failing "equals" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingAssertEqualsAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-is-assertion.yaml'),
            ],
            'failing "not-equals" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingAssertNotEqualsAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-is-not-assertion.yaml'),
            ],
            'failing "matches regular expression" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingAssertMatchesRegularExpressionAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-matches-assertion.yaml'),
            ],
            'failing with InvalidLocatorException' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingWithInvalidLocatorException.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-assertion-with-invalid-locator.yaml'
                ),
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function passingTestsDataProvider(): array
    {
        $root = getcwd();

        return [
            'passing 01' => [
                'testPath' => $root . '/tests/Fixtures/Tests/Passing01.php',
                'expectedExitCode' => 0,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/passed-two-steps-one-action-two-assertions-one-assertion.yaml'
                ),
            ],
            'passing with data provider' => [
                'testPath' => $root . '/tests/Fixtures/Tests/PassingWithDataProvider.php',
                'expectedExitCode' => 0,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/passed-with-data-provider.yaml'),
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
            'terminated, lastException set during setupBeforeClass' => [
                'testPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInSetupBeforeClassTest.php',
                'expectedExitCode' => 2,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/terminated.yaml'),
            ],
        ];
    }
}
