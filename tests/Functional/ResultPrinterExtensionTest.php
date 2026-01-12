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
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Failed
                    PHPUnit\Event\Test\Finished
                    status: failed
                    step one
                    {
                        "statement-type": "action",
                        "source": "click $\".selector\"",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "type": "click",
                        "arguments": "$\".selector\""
                    }
                    {
                        "statement-type": "assertion",
                        "source": "$page.url is \"http:\/\/www.example.com\"",
                        "index": 1,
                        "identifier": "$page.url",
                        "value": "\"http:\/\/www.example.com\"",
                        "operator": "is"
                    }
                    {
                        "statement-type": "assertion",
                        "source": "$\".selector\" exists",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "operator": "exists"
                    }
                    failed assertion: $".selector" exists
                    expected: "true"
                    actual: "false"
                    EOD,
            ],
            'failing derived assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingDerivedAssertTrueAssertion.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Failed
                    PHPUnit\Event\Test\Finished
                    status: failed
                    step one
                    {
                        "statement-type": "action",
                        "source": "click $\".selector\"",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "type": "click",
                        "arguments": "$\".selector\""
                    }
                    {
                        "statement-type": "assertion",
                        "source": "$page.url is \"http:\/\/www.example.com\"",
                        "index": 1,
                        "identifier": "$page.url",
                        "value": "\"http:\/\/www.example.com\"",
                        "operator": "is"
                    }
                    {
                        "statement-type": "assertion",
                        "source": "$page.title is \"Foo\"",
                        "index": 2,
                        "identifier": "$page.title",
                        "value": "\"Foo\"",
                        "operator": "is"
                    }
                    failed assertion: $".selector" exists
                    expected: "true"
                    actual: "false"
                    EOD,
            ],
            'failing action' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingAction.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Failed
                    PHPUnit\Event\Test\Finished
                    status: failed
                    step one
                    {
                        "statement-type": "action",
                        "source": "click $\".selector\"",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "type": "click",
                        "arguments": "$\".selector\""
                    }
                    {
                        "statement-type": "assertion",
                        "source": "$page.url is \"http:\/\/www.example.com\"",
                        "index": 1,
                        "identifier": "$page.url",
                        "value": "\"http:\/\/www.example.com\"",
                        "operator": "is"
                    }
                    assertion failure statement: click $".selector"
                    reason: "action-failed"
                    exception class: "RuntimeException"
                    exception code: "0"
                    exception message: "Runtime exception executing action"
                    context: "[]"
                    EOD,
            ],
            'failing "string contains string" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingStringContainsStringAssertion.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Failed
                    PHPUnit\Event\Test\Finished
                    status: failed
                    step one
                    {
                        "statement-type": "assertion",
                        "source": "$\".selector\" includes \"value\"",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "value": "\"value\"",
                        "operator": "includes"
                    }
                    failed assertion: $".selector" includes "value"
                    expected: "string-contains-string-expected-value"
                    actual: "string-contains-string-examined-value"
                    EOD,
            ],
            'failing "string not contains string" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingStringNotContainsStringAssertion.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Failed
                    PHPUnit\Event\Test\Finished
                    status: failed
                    step one
                    {
                        "statement-type": "assertion",
                        "source": "$\".selector\" excludes \"value\"",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "value": "\"value\"",
                        "operator": "excludes"
                    }
                    failed assertion: $".selector" excludes "value"
                    expected: "string-not-contains-string"
                    actual: "string-not-contains-string-within"
                    EOD,
            ],
            'failing "equals" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingAssertEqualsAssertion.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Failed
                    PHPUnit\Event\Test\Finished
                    status: failed
                    step one
                    {
                        "statement-type": "assertion",
                        "source": "$\".selector\" is \"value\"",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "value": "\"value\"",
                        "operator": "is"
                    }
                    failed assertion: $".selector" is "value"
                    expected: "assert-equals-expected-value"
                    actual: "assert-equals-actual-value"
                    EOD,
            ],
            'failing "not-equals" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingAssertNotEqualsAssertion.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Failed
                    PHPUnit\Event\Test\Finished
                    status: failed
                    step one
                    {
                        "statement-type": "assertion",
                        "source": "$\".selector\" is-not \"value\"",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "value": "\"value\"",
                        "operator": "is-not"
                    }
                    failed assertion: $".selector" is-not "value"
                    expected: "assert-not-equals-value"
                    actual: "assert-not-equals-value"
                    EOD,
            ],
            'failing "matches regular expression" assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingAssertMatchesRegularExpressionAssertion.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Failed
                    PHPUnit\Event\Test\Finished
                    status: failed
                    step one
                    {
                        "statement-type": "assertion",
                        "source": "$\".selector\" matches \"\/^value\/\"",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "value": "\"\/^value\/\"",
                        "operator": "matches"
                    }
                    failed assertion: $".selector" matches "/^value/"
                    expected: "/pattern/"
                    actual: "assert-matches-regular-expression-expected-value"
                    EOD,
            ],
            'failing with InvalidLocatorException' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingWithInvalidLocatorException.php',
                'expectedExitCode' => 1,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Failed
                    PHPUnit\Event\Test\Finished
                    status: failed
                    step one
                    {
                        "statement-type": "assertion",
                        "source": "$\".selector\" exists",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "operator": "exists"
                    }
                    assertion failure statement: $".selector" exists
                    reason: "locator-invalid"
                    exception class: "webignition\SymfonyDomCrawlerNavigator\Exception\InvalidLocatorException"
                    exception code: "0"
                    exception message: "Invalid CSS selector locator $".selector""
                    context: "{"locator":"$\".selector\"","type":"css"}"
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
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Passed
                    PHPUnit\Event\Test\Finished
                    status: passed
                    step one
                    {
                        "statement-type": "action",
                        "source": "click $\".selector\"",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "type": "click",
                        "arguments": "$\".selector\""
                    }
                    {
                        "statement-type": "assertion",
                        "source": "$page.url is \"http:\/\/www.example.com\"",
                        "index": 1,
                        "identifier": "$page.url",
                        "value": "\"http:\/\/www.example.com\"",
                        "operator": "is"
                    }
                    {
                        "statement-type": "assertion",
                        "source": "$page.title is \"Foo\"",
                        "index": 2,
                        "identifier": "$page.title",
                        "value": "\"Foo\"",
                        "operator": "is"
                    }
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Passed
                    PHPUnit\Event\Test\Finished
                    status: passed
                    step two
                    {
                        "statement-type": "assertion",
                        "source": "$page.url is \"http:\/\/www.example.com\"",
                        "index": 0,
                        "identifier": "$page.url",
                        "value": "\"http:\/\/www.example.com\"",
                        "operator": "is"
                    }
                    EOD,
            ],
            'passing with data provider' => [
                'testPath' => $root . '/tests/Fixtures/Tests/PassingWithDataProvider.php',
                'expectedExitCode' => 0,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Passed
                    PHPUnit\Event\Test\Finished
                    status: passed
                    provided data:
                    {"foo":1,"bar":"two","fooBar":true}
                    step one
                    {
                        "statement-type": "action",
                        "source": "set $\".selector\" to $data.value",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "type": "set",
                        "arguments": "$data.value"
                    }
                    {
                        "statement-type": "assertion",
                        "source": "$page.url is \"http:\/\/www.example.com\"",
                        "index": 1,
                        "identifier": "$page.url",
                        "value": "\"http:\/\/www.example.com\"",
                        "operator": "is"
                    }
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Passed
                    PHPUnit\Event\Test\Finished
                    status: passed
                    provided data:
                    {"foo":7,"bar":"eight","fooBar":true}
                    step one
                    {
                        "statement-type": "action",
                        "source": "set $\".selector\" to $data.value",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "type": "set",
                        "arguments": "$data.value"
                    }
                    {
                        "statement-type": "assertion",
                        "source": "$page.url is \"http:\/\/www.example.com\"",
                        "index": 1,
                        "identifier": "$page.url",
                        "value": "\"http:\/\/www.example.com\"",
                        "operator": "is"
                    }
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Passed
                    PHPUnit\Event\Test\Finished
                    status: passed
                    provided data:
                    {"foo":9,"bar":"ten","fooBar":false}
                    step one
                    {
                        "statement-type": "action",
                        "source": "set $\".selector\" to $data.value",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "type": "set",
                        "arguments": "$data.value"
                    }
                    {
                        "statement-type": "assertion",
                        "source": "$page.url is \"http:\/\/www.example.com\"",
                        "index": 1,
                        "identifier": "$page.url",
                        "value": "\"http:\/\/www.example.com\"",
                        "operator": "is"
                    }
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
