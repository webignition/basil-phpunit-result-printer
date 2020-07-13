<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Services;

use Mockery\MockInterface;
use webignition\BaseBasilTestCase\BasilTestCaseInterface;

class BasilTestCaseFactory
{
    /**
     * @param array<mixed> $properties
     *
     * @return BasilTestCaseInterface|MockInterface
     */
    public static function create(array $properties): BasilTestCaseInterface
    {
        $basilStepName = $properties['basilStepName'] ?? null;
        $status = $properties['status'] ?? null;
        $handledStatements = $properties['handledStatements'] ?? [];
        $expectedValue = $properties['expectedValue'] ?? null;
        $examinedValue = $properties['examinedValue'] ?? null;
        $lastException = $properties['lastException'] ?? null;
        $currentDataSet = $properties['currentDataSet'] ?? null;

        $testCase = \Mockery::mock(BasilTestCaseInterface::class);

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

        return $testCase;
    }
}
