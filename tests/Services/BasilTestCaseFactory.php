<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Services;

use Mockery\MockInterface;
use webignition\BaseBasilTestCase\BasilTestCaseInterface;
use webignition\BasilModels\Test\ConfigurationInterface;

class BasilTestCaseFactory
{
    /**
     * @param array<mixed> $properties
     *
     * @return BasilTestCaseInterface|MockInterface
     */
    public static function create(array $properties): BasilTestCaseInterface
    {
        $testPath = $properties['basilTestPath'] ?? null;
        $basilStepName = $properties['basilStepName'] ?? null;
        $status = $properties['status'] ?? null;
        $handledStatements = $properties['handledStatements'] ?? [];
        $expectedValue = $properties['expectedValue'] ?? null;
        $examinedValue = $properties['examinedValue'] ?? null;
        $lastException = $properties['lastException'] ?? null;
        $currentDataSet = $properties['currentDataSet'] ?? null;
        $basilTestConfiguration = $properties['basilTestConfiguration'] ?? null;

        $testCase = \Mockery::mock(BasilTestCaseInterface::class);

        if (is_string($testPath)) {
            $testCase
                ->shouldReceive('getBasilTestPath')
                ->andReturn($testPath);
        }

        $testCase
            ->shouldReceive('getBasilStepName')
            ->andReturn($basilStepName);

        $testCase
            ->shouldReceive('getStatus')
            ->andReturn($status);

        $testCase
            ->shouldReceive('getHandledStatements')
            ->andReturn($handledStatements);

        $testCase
            ->shouldReceive('getExpectedValue')
            ->andReturn($expectedValue);

        $testCase
            ->shouldReceive('getExaminedValue')
            ->andReturn($examinedValue);

        $testCase
            ->shouldReceive('getLastException')
            ->andReturn($lastException);

        $testCase
            ->shouldReceive('getCurrentDataSet')
            ->andReturn($currentDataSet);

        if ($basilTestConfiguration instanceof ConfigurationInterface) {
            $testCase
                ->shouldReceive('getBasilTestConfiguration')
                ->andReturn($basilTestConfiguration);
        }

        return $testCase;
    }
}
