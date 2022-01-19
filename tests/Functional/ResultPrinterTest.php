<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Functional;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PHPUnit\TextUI\TestRunner;
use webignition\BasilPhpUnitResultPrinter\ResultPrinter;
use webignition\BasilPhpUnitResultPrinter\Tests\Services\FixtureLoader;
use webignition\YamlDocumentSetParser\Parser;

class ResultPrinterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const YAML_DOCUMENT_START = '---';

    /**
     * @dataProvider terminatedDataProvider
     *
     * @param array<mixed> $expectedPartialOutput
     */
    public function testExceptionHandling(
        string $phpUnitTestPath,
        array $expectedPartialOutput
    ): void {
        $phpunitCommand = './vendor/bin/phpunit --printer="' . ResultPrinter::class . '" ' . $phpUnitTestPath;

        $phpunitOutput = [];
        $exitCode = null;

        exec($phpunitCommand, $phpunitOutput, $exitCode);
        self::assertSame(TestRunner::EXCEPTION_EXIT, $exitCode);

        $outputYaml = $this->getYamlOutputBody($phpunitOutput);
        $outputData = (new Parser())->parse($outputYaml);

        self::assertIsArray($outputData);
        self::assertCount(count($expectedPartialOutput), $outputData);

        $exceptionData = array_pop($outputData);
        $exceptionData = is_array($exceptionData) ? $exceptionData : [];

        $expectedPartialExceptionData = array_pop($expectedPartialOutput);
        $expectedPartialExceptionData = is_array($expectedPartialExceptionData) ? $expectedPartialExceptionData : [];

        foreach ($expectedPartialOutput as $index => $expectedData) {
            self::assertSame($expectedData, $outputData[$index]);
        }

        self::assertExceptionData($expectedPartialExceptionData, $exceptionData);
    }

    /**
     * @return array<mixed>
     */
    public function terminatedDataProvider(): array
    {
        $root = getcwd();
        $yamlDocumentSetParser = new Parser();

        return [
            'terminated, RuntimeException thrown during first step' => [
                'phpUnitTestPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInFirstStepTest.php',
                'expectedPartialOutput' => $yamlDocumentSetParser->parse((string) FixtureLoader::load(
                    '/ResultPrinter/failed-runtime-exception-single-test-first-step-partial.yaml'
                )),
            ],
            'terminated, RuntimeException thrown during second step' => [
                'phpUnitTestPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInSecondStepTest.php',
                'expectedPartialOutput' => $yamlDocumentSetParser->parse((string) FixtureLoader::load(
                    '/ResultPrinter/failed-runtime-exception-single-test-second-step-partial.yaml'
                )),
            ],
            'terminated, lastException set during setupBeforeClass' => [
                'phpUnitTestPath' => $root . '/tests/Fixtures/Tests/SetsLastExceptionInSetupBeforeClassTest.php',
                'expectedPartialOutput' => $yamlDocumentSetParser->parse((string) FixtureLoader::load(
                    '/ResultPrinter/failed-set-last-exception-in-setup-before-class.yaml'
                )),
            ],
        ];
    }

    /**
     * @param string[] $output
     */
    private function getYamlOutputBody(array $output): string
    {
        $hasFoundYamlDocumentStart = false;

        $body = '';

        foreach ($output as $line) {
            if (self::YAML_DOCUMENT_START === $line) {
                $hasFoundYamlDocumentStart = true;
            }

            if ($hasFoundYamlDocumentStart) {
                $body .= $line . "\n";
            }
        }

        return $body;
    }

    /**
     * @param array<mixed> $expectedSubset
     * @param array<mixed> $exceptionData
     */
    private static function assertExceptionData(array $expectedSubset, array $exceptionData): void
    {
        foreach ($expectedSubset as $key => $value) {
            self::assertArrayHasKey($key, $exceptionData);
            self::assertSame($value, $exceptionData[$key]);
        }

        self::assertArrayHasKey('trace', $exceptionData);
    }
}
