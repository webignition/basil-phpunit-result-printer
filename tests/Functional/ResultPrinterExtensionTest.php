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
            'failing assertion' => [
                'testPath' => $root . '/tests/Fixtures/Tests/FailingAssertion.php',
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
                    failed assertion: $page.title is "Foo"
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
                    failed action: click $".selector"
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
            'terminated, RuntimeException thrown during first step' => [
                'testPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInFirstStepTest.php',
                'expectedExitCode' => 2,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Errored
                    RuntimeException: Exception thrown in first step
                    PHPUnit\Event\Test\Finished
                    status: terminated
                    step one
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
            'terminated, RuntimeException thrown during second step' => [
                'testPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInSecondStepTest.php',
                'expectedExitCode' => 2,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Passed
                    PHPUnit\Event\Test\Finished
                    status: passed
                    step one
                    {
                        "statement-type": "assertion",
                        "source": "$page.url is \"http:\/\/www.example.com\"",
                        "index": 0,
                        "identifier": "$page.url",
                        "value": "\"http:\/\/www.example.com\"",
                        "operator": "is"
                    }
                    PHPUnit\Event\Test\Prepared
                    PHPUnit\Event\Test\Errored
                    RuntimeException: Exception thrown in second step
                    PHPUnit\Event\Test\Finished
                    status: terminated
                    step two
                    {
                        "statement-type": "assertion",
                        "source": "$page.title is \"Foo\"",
                        "index": 0,
                        "identifier": "$page.title",
                        "value": "\"Foo\"",
                        "operator": "is"
                    }
                    EOD,
            ],
            'terminated, lastException set during setupBeforeClass' => [
                'testPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInSetupBeforeClassTest.php',
                'expectedExitCode' => 2,
                'expectedPhpunitOutput' => <<<'EOD'
                    PHPUnit\Event\Test\BeforeFirstTestMethodErrored
                    RuntimeException: Exception thrown in setUpBeforeClass
                    EOD,
            ],
        ];
    }
}
