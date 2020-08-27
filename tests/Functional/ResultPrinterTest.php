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
     * @param string $phpUnitTestPath
     * @param array<mixed> $expectedPartialOutput
     */
    public function testExecutedTestThrowsAnException(
        string $phpUnitTestPath,
        array $expectedPartialOutput
    ) {
        $phpunitCommand = './vendor/bin/phpunit --printer="' . ResultPrinter::class . '" ' . $phpUnitTestPath;

        $phpunitOutput = [];
        $exitCode = null;

        exec($phpunitCommand, $phpunitOutput, $exitCode);
        self::assertSame(TestRunner::EXCEPTION_EXIT, $exitCode);

        $yamlDocumentSetParser = new Parser();

        $outputYaml = $this->getYamlOutputBody($phpunitOutput);
        $outputData = $yamlDocumentSetParser->parse($outputYaml);


        self::assertIsArray($outputData);
        self::assertCount(2, $outputData);

        self::assertIsArray($outputData[0]);
        self::assertSame($expectedPartialOutput[0], $outputData[0]);

        self::assertIsArray($outputData[1]);
        self::assertExceptionData($expectedPartialOutput[1], $outputData[1]);
    }

    public function terminatedDataProvider(): array
    {
        $root = getcwd();
        $yamlDocumentSetParser = new Parser();

        return [
            'terminated, no steps handled, RuntimeException thrown' => [
                'phpUnitTestPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionOnFirstStepTest.php',
                'expectedPartialOutput' => $yamlDocumentSetParser->parse((string) FixtureLoader::load(
                    '/ResultPrinter/failed-runtime-exception-single-test-first-step-partial.yaml'
                )),
            ],
        ];
    }

    /**
     * @param string[] $output
     *
     * @return string
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
