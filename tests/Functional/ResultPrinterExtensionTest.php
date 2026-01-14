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
            'failing element exists assertion as third statement' => [
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
            'failing element includes literal assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementIncludesLiteralAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-element-includes-literal-assertion.yaml'
                ),
            ],
            'failing element excludes literal assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementExcludesLiteralAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-element-excludes-literal-assertion.yaml'
                ),
            ],
            'failing element is literal assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementIsLiteralAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-element-is-literal-assertion.yaml'),
            ],
            'failing element is element assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementIsElementAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-element-is-element-assertion.yaml'),
            ],
            'failing page property is data parameter assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedPagePropertyIsDataParameterAssertionTest.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-page-property-is-data-parameter-assertion.yaml'),
            ],
            'failing page property is environment parameter assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedPagePropertyIsEnvironmentParameterAssertionTest.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-page-property-is-environment-parameter-assertion.yaml'),
            ],
            'failing page property is element assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedPagePropertyIsElementAssertionTest.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-page-property-is-element-assertion.yaml'),
            ],
            'failing browser property is literal assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedBrowserPropertyIsLiteralAssertionTest.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-browser-property-is-literal-assertion.yaml'),
            ],
            'failing element is-not literal assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementIsNotLiteralAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load('/ResultPrinterExtension/failed-element-is-not-literal-assertion.yaml'),
            ],
            'failing element matches literal assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementMatchesLiteralAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-element-matches-literal-assertion.yaml'
                ),
            ],
            'failing with InvalidLocatorException' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementIsAssertionWithInvalidLocator.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-element-is-assertion-with-invalid-locator.yaml'
                ),
            ],
            'failing attribute is-regexp assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedAttributeIsRegexpAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-attribute-is-regexp-assertion.yaml'
                ),
            ],
            'failing element is-regexp assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailedElementIsRegexpAssertion.php',
                'expectedExitCode' => 1,
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinterExtension/failed-element-is-regexp-assertion.yaml'
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
