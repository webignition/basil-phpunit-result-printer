<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Functional;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PHPUnit\TextUI\TestRunner;
use Symfony\Component\Yaml\Parser as YamlParser;
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
     * @param array<int, string> $expectedPartialDocumentContents
     */
    public function testExceptionHandling(string $phpUnitTestPath, array $expectedPartialDocumentContents): void
    {
        $phpunitCommand = './vendor/bin/phpunit --printer="' . ResultPrinter::class . '" ' . $phpUnitTestPath;

        $phpunitOutput = [];
        $exitCode = null;

        exec($phpunitCommand, $phpunitOutput, $exitCode);
        self::assertSame(TestRunner::EXCEPTION_EXIT, $exitCode);

        $outputYaml = $this->getYamlOutputBody($phpunitOutput);
        $documents = (new Parser())->parse($outputYaml);

        self::assertIsArray($documents);
        self::assertCount(count($expectedPartialDocumentContents), $documents);

        $yamlParser = new YamlParser();

        foreach ($expectedPartialDocumentContents as $index => $expectedPartialDocumentContent) {
            $document = $yamlParser->parse($documents[$index] ?? '');
            self::assertIsArray($document);

            $expectedPartialDocument = $yamlParser->parse($expectedPartialDocumentContent);
            self::assertIsArray($expectedPartialDocument);

            self::assertDocument($expectedPartialDocument, $document);
        }
    }

    /**
     * @return array<mixed>
     */
    public function terminatedDataProvider(): array
    {
        $root = getcwd();
        $yamlDocumentSetParser = new Parser();

        $isGithubRunner = array_key_exists('GITHUB_ACTIONS', $_SERVER);

        return [
            'terminated, RuntimeException thrown during first step' => [
                'phpUnitTestPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInFirstStepTest.php',
                'expectedPartialDocumentContents' => (function (Parser $yamlDocumentSetParser, bool $isGithubRunner) {
                    $fixturePath = $isGithubRunner
                        ? '/ResultPrinter/failed-exception-wrapper-exception-single-test-first-step-partial.yaml'
                        : '/ResultPrinter/failed-runtime-exception-single-test-first-step-partial.yaml';

                    return $yamlDocumentSetParser->parse((string) FixtureLoader::load($fixturePath));
                })($yamlDocumentSetParser, $isGithubRunner),
            ],
            'terminated, RuntimeException thrown during second step' => [
                'phpUnitTestPath' => $root . '/tests/Fixtures/Tests/ThrowsRuntimeExceptionInSecondStepTest.php',
                'expectedPartialDocumentContents' => (function (Parser $yamlDocumentSetParser, bool $isGithubRunner) {
                    $fixturePath = $isGithubRunner
                        ? '/ResultPrinter/failed-exception-wrapper-exception-single-test-second-step-partial.yaml'
                        : '/ResultPrinter/failed-runtime-exception-single-test-second-step-partial.yaml';

                    return $yamlDocumentSetParser->parse((string) FixtureLoader::load($fixturePath));
                })($yamlDocumentSetParser, $isGithubRunner),
            ],
            'terminated, lastException set during setupBeforeClass' => [
                'phpUnitTestPath' => $root . '/tests/Fixtures/Tests/SetsLastExceptionInSetupBeforeClassTest.php',
                'expectedPartialDocumentContents' => $yamlDocumentSetParser->parse((string) FixtureLoader::load(
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
     * @param array<mixed> $expected
     * @param array<mixed> $actual
     */
    private static function assertDocument(array $expected, array $actual): void
    {
        self::assertArrayHasKey('type', $expected);
        self::assertArrayHasKey('payload', $expected);

        self::assertArrayHasKey('type', $actual);
        self::assertArrayHasKey('payload', $actual);

        $expectedType = $expected['type'];
        self::assertSame($expectedType, $actual['type']);

        if ('exception' === $expectedType) {
            $expectedPayload = $expected['payload'];
            $expectedPayload = is_array($expectedPayload) ? $expectedPayload : [];

            $outputPayload = $actual['payload'];
            $outputPayload = is_array($outputPayload) ? $outputPayload : [];

            self::assertExceptionData($expectedPayload, $outputPayload);
        } else {
            self::assertSame($expected, $actual);
        }
    }

    /**
     * @param array<mixed> $expected
     * @param array<mixed> $actual
     */
    private static function assertExceptionData(array $expected, array $actual): void
    {
        foreach ($expected as $key => $value) {
            self::assertArrayHasKey($key, $actual);
            self::assertSame($value, $actual[$key]);
        }

        self::assertArrayHasKey('trace', $actual);
    }
}
