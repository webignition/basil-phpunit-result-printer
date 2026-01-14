<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Functional;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Parser as YamlParser;
use webignition\BasilPhpUnitResultPrinter\ResultPrinter;
use webignition\BasilPhpUnitResultPrinter\Tests\Services\FixtureLoader;
use webignition\YamlDocumentSetParser\Parser;

class ResultPrinterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const YAML_DOCUMENT_START = '---';

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
