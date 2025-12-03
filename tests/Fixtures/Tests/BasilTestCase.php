<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\BaseTestRunner;
use webignition\BaseBasilTestCase\BasilTestCaseInterface;
use webignition\BaseBasilTestCase\ClientManager;
use webignition\BasilModels\Model\DataSet\DataSetInterface;
use webignition\DomElementIdentifier\ElementIdentifierInterface;

class BasilTestCase extends TestCase implements BasilTestCaseInterface
{
    protected static ?\Throwable $lastException = null;
    private string $basilStepName = '';
    private static string $basilTestPath = '';

    public static function setBasilTestPath(string $testPath): void
    {
        self::$basilTestPath = $testPath;
    }

    public function getBasilTestPath(): string
    {
        return self::$basilTestPath;
    }

    public function setBasilStepName(string $stepName): void
    {
        $this->basilStepName = $stepName;
    }

    public function getBasilStepName(): string
    {
        return $this->basilStepName;
    }

    public function getHandledStatements(): array
    {
        return [];
    }

    public function setExaminedValue(?string $examinedValue): void {}

    public function setExpectedValue(?string $expectedValue): void {}

    public function getExaminedValue(): ?string
    {
        return null;
    }

    public function getExpectedValue(): ?string
    {
        return null;
    }

    public function setBooleanExaminedValue(bool $examinedValue): void {}

    public function setBooleanExpectedValue(bool $expectedValue): void {}

    public function getBooleanExaminedValue(): ?bool
    {
        return null;
    }

    public function getBooleanExpectedValue(): ?bool
    {
        return null;
    }

    public function getExaminedElementIdentifier(): ?ElementIdentifierInterface
    {
        return null;
    }

    public function getExpectedElementIdentifier(): ?ElementIdentifierInterface
    {
        return null;
    }

    public static function staticSetLastException(\Throwable $exception): void
    {
        self::$lastException = $exception;
    }

    public function setLastException(\Throwable $exception): void
    {
        self::$lastException = $exception;
    }

    public function getLastException(): ?\Throwable
    {
        return self::$lastException;
    }

    public function setCurrentDataSet(?DataSetInterface $dataSet): void {}

    public function getCurrentDataSet(): ?DataSetInterface
    {
        return null;
    }

    public static function staticGetLastException(): ?\Throwable
    {
        return self::$lastException;
    }

    public static function setClientManager(ClientManager $clientManager): void {}

    public function getStatus(): int
    {
        return self::$lastException instanceof \Throwable
            ? BaseTestRunner::STATUS_FAILURE
            : parent::getStatus();
    }

    public static function hasException(): bool
    {
        return null !== self::staticGetLastException();
    }
}
