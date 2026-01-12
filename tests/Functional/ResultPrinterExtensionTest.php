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
    public static function failingTestsDataProvider(): array
    {
        $root = getcwd();

        return [
            'failing regular assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingRegularAssertTrueAssertion.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    ---
                    type: step
                    payload:
                      name: 'step one'
                      status: failed
                      statements:
                        -
                          type: action
                          source: 'click $".selector"'
                          status: passed
                        -
                          type: assertion
                          source: '$page.url is "http://www.example.com"'
                          status: passed
                        -
                          type: assertion
                          source: '$".selector" exists'
                          status: failed
                          summary:
                            operator: exists
                            source:
                              type: node
                              body:
                                type: element
                                identifier:
                                  source: '$".selector"'
                                  properties:
                                    type: css
                                    locator: .selector
                                    position: 1
                    ...
                    EOD,
            ],
            'failing derived assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingDerivedAssertTrueAssertion.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    ---
                    type: step
                    payload:
                      name: 'step one'
                      status: failed
                      statements:
                        -
                          type: assertion
                          source: '$".selector" exists'
                          status: failed
                          transformations:
                            -
                              type: derivation
                              source: 'click $".selector"'
                          summary:
                            operator: exists
                            source:
                              type: node
                              body:
                                type: element
                                identifier:
                                  source: '$".selector"'
                                  properties:
                                    type: css
                                    locator: .selector
                                    position: 1
                    ...
                    EOD,
            ],
            'failing action' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingAction.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    ---
                    type: step
                    payload:
                      name: 'step one'
                      status: failed
                      statements:
                        -
                          type: action
                          source: 'click $".selector"'
                          status: failed
                          exception:
                            type: unknown
                            body:
                              class: RuntimeException
                              message: 'Runtime exception executing action'
                    ...
                    EOD,
            ],
            'failing "string contains string" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingStringContainsStringAssertion.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    ---
                    type: step
                    payload:
                      name: 'step one'
                      status: failed
                      statements:
                        -
                          type: assertion
                          source: '$".selector" includes "value"'
                          status: failed
                          summary:
                            operator: includes
                            expected:
                              value: string-contains-string-expected-value
                              source:
                                type: scalar
                                body:
                                  type: literal
                                  value: '"value"'
                            actual:
                              value: string-contains-string-examined-value
                              source:
                                type: node
                                body:
                                  type: element
                                  identifier:
                                    source: '$".selector"'
                                    properties:
                                      type: css
                                      locator: .selector
                                      position: 1
                    ...
                    EOD,
            ],
            'failing "string not contains string" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingStringNotContainsStringAssertion.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    ---
                    type: step
                    payload:
                      name: 'step one'
                      status: failed
                      statements:
                        -
                          type: assertion
                          source: '$".selector" excludes "value"'
                          status: failed
                          summary:
                            operator: excludes
                            expected:
                              value: string-not-contains-string
                              source:
                                type: scalar
                                body:
                                  type: literal
                                  value: '"value"'
                            actual:
                              value: string-not-contains-string-within
                              source:
                                type: node
                                body:
                                  type: element
                                  identifier:
                                    source: '$".selector"'
                                    properties:
                                      type: css
                                      locator: .selector
                                      position: 1
                    ...
                    EOD,
            ],
            'failing "equals" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingAssertEqualsAssertion.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    ---
                    type: step
                    payload:
                      name: 'step one'
                      status: failed
                      statements:
                        -
                          type: assertion
                          source: '$".selector" is "value"'
                          status: failed
                          summary:
                            operator: is
                            expected:
                              value: assert-equals-expected-value
                              source:
                                type: scalar
                                body:
                                  type: literal
                                  value: '"value"'
                            actual:
                              value: assert-equals-actual-value
                              source:
                                type: node
                                body:
                                  type: element
                                  identifier:
                                    source: '$".selector"'
                                    properties:
                                      type: css
                                      locator: .selector
                                      position: 1
                    ...
                    EOD,
            ],
            'failing "not-equals" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingAssertNotEqualsAssertion.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    ---
                    type: step
                    payload:
                      name: 'step one'
                      status: failed
                      statements:
                        -
                          type: assertion
                          source: '$".selector" is-not "value"'
                          status: failed
                          summary:
                            operator: is-not
                            expected:
                              value: assert-not-equals-value
                              source:
                                type: scalar
                                body:
                                  type: literal
                                  value: '"value"'
                            actual:
                              value: assert-not-equals-value
                              source:
                                type: node
                                body:
                                  type: element
                                  identifier:
                                    source: '$".selector"'
                                    properties:
                                      type: css
                                      locator: .selector
                                      position: 1
                    ...
                    EOD,
            ],
            'failing "matches regular expression" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingAssertMatchesRegularExpressionAssertion.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    ---
                    type: step
                    payload:
                      name: 'step one'
                      status: failed
                      statements:
                        -
                          type: assertion
                          source: '$".selector" matches "/^value/"'
                          status: failed
                          summary:
                            operator: matches
                            expected:
                              value: /pattern/
                              source:
                                type: scalar
                                body:
                                  type: literal
                                  value: '"/^value/"'
                            actual:
                              value: assert-matches-regular-expression-expected-value
                              source:
                                type: node
                                body:
                                  type: element
                                  identifier:
                                    source: '$".selector"'
                                    properties:
                                      type: css
                                      locator: .selector
                                      position: 1
                    ...
                    EOD,
            ],
            'failing with InvalidLocatorException' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingWithInvalidLocatorException.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    ---
                    type: step
                    payload:
                      name: 'step one'
                      status: failed
                      statements:
                        -
                          type: assertion
                          source: '$".selector" exists'
                          status: failed
                          exception:
                            type: invalid-locator
                            body:
                              type: css
                              locator: '$".selector"'
                              source:
                                type: node
                                body:
                                  type: element
                                  identifier:
                                    source: '$".selector"'
                                    properties:
                                      type: css
                                      locator: .selector
                                      position: 1
                    ...
                    EOD,
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
                'expectedPhpunitOutput' => <<<'EOD'
                    ---
                    type: step
                    payload:
                      name: 'step one'
                      status: passed
                      statements:
                        -
                          type: action
                          source: 'click $".selector"'
                          status: passed
                        -
                          type: assertion
                          source: '$page.url is "http://www.example.com"'
                          status: passed
                        -
                          type: assertion
                          source: '$page.title is "Foo"'
                          status: passed
                    ...
                    ---
                    type: step
                    payload:
                      name: 'step two'
                      status: passed
                      statements:
                        -
                          type: assertion
                          source: '$page.url is "http://www.example.com"'
                          status: passed
                    ...
                    EOD,
            ],
            'passing with data provider' => [
                'testPath' => $root . '/tests/Fixtures/Tests/PassingWithDataProvider.php',
                'expectedExitCode' => 0,
                'expectedPhpunitOutput' => <<<'EOD'
                    ---
                    type: step
                    payload:
                      name: 'step one'
                      status: passed
                      statements:
                        -
                          type: action
                          source: 'set $".selector" to $data.value'
                          status: passed
                        -
                          type: assertion
                          source: '$page.url is "http://www.example.com"'
                          status: passed
                      data:
                        foo: 1
                        bar: two
                        fooBar: true
                    ...
                    ---
                    type: step
                    payload:
                      name: 'step one'
                      status: passed
                      statements:
                        -
                          type: action
                          source: 'set $".selector" to $data.value'
                          status: passed
                        -
                          type: assertion
                          source: '$page.url is "http://www.example.com"'
                          status: passed
                      data:
                        foo: 7
                        bar: eight
                        fooBar: true
                    ...
                    ---
                    type: step
                    payload:
                      name: 'step one'
                      status: passed
                      statements:
                        -
                          type: action
                          source: 'set $".selector" to $data.value'
                          status: passed
                        -
                          type: assertion
                          source: '$page.url is "http://www.example.com"'
                          status: passed
                      data:
                        foo: 9
                        bar: ten
                        fooBar: false
                    ...
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
            'terminated, lastException set during setupBeforeClass' => [
                'testPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInSetupBeforeClassTest.php',
                'expectedExitCode' => 2,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\BeforeFirstTestMethodErrored
                    status: terminated
                    throwable: "RuntimeException: Exception thrown in setUpBeforeClass"
                    EOD,
            ],
        ];
    }
}
