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
            'failing exists assertion for element as third statement' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementExistsAssertionAsThirdStatement.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-element-exists-assertion-as-third-statement.yaml'
                ),
            ],
            'failing attribute exists assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedAttributeExistsAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-attribute-exists-assertion.yaml'
                ),
            ],
            'failing descendant css > css element exists assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedDescendantCssCssElementExistsAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-descendant-css-css-element-exists-assertion.yaml'
                ),
            ],
            'failing descendant css > xpath element exists assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedDescendantCssXpathElementExistsAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-descendant-css-xpath-element-exists-assertion.yaml'
                ),
            ],
            'failing derived exists assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedDerivedExistsAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-derived-exists-assertion.yaml'),
            ],
            'failing click action' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedClickAction.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-click-action.yaml'),
            ],
            'failing includes assertion for element' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementIncludesAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-element-includes-assertion.yaml'
                ),
            ],
            'failing excludes assertion for element' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementExcludesAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-element-excludes-assertion.yaml'
                ),
            ],
            'failing is assertion for element' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementIsAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-element-is-assertion.yaml'),
            ],
            'failing page property is data parameter assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedBrowserPropertyIsDataParameterAssertionTest.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-browser-property-is-data-parameter-assertion.yaml'),
            ],
            'failing page property is environment parameter assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedBrowserPropertyIsEnvironmentParameterAssertionTest.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-browser-property-is-environment-parameter-assertion.yaml'),
            ],
            'failing is-not assertion for element' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementIsNotAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-element-is-not-assertion.yaml'),
            ],
            'failing matches assertion for element' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementMatchesAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-element-matches-assertion.yaml'
                ),
            ],
            'failing with InvalidLocatorException' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementIsAssertionWithInvalidLocator.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-element-is-assertion-with-invalid-locator.yaml'
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
