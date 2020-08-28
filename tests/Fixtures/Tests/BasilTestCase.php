<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use PHPUnit\Framework\TestCase;
use webignition\BaseBasilTestCase\BasilTestCaseInterface;
use webignition\BaseBasilTestCase\ClientManager;
use webignition\BasilModels\DataSet\DataSetInterface;
use webignition\BasilModels\Test\ConfigurationInterface;
use webignition\DomElementIdentifier\ElementIdentifierInterface;

class BasilTestCase extends TestCase implements BasilTestCaseInterface
{
    private string $basilStepName = '';

    public static function setBasilTestPath(string $testPath): void
    {
    }

    public static function getBasilTestPath(): string
    {
        return '';
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

    public function setExaminedValue(?string $examinedValue): void
    {
    }

    public function setExpectedValue(?string $expectedValue): void
    {
    }

    public function getExaminedValue(): ?string
    {
        return null;
    }

    public function getExpectedValue(): ?string
    {
        return null;
    }

    public function setBooleanExaminedValue(bool $examinedValue): void
    {
    }

    public function setBooleanExpectedValue(bool $expectedValue): void
    {
    }

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

    public function getLastException(): ?\Throwable
    {
        return null;
    }

    public function setCurrentDataSet(?DataSetInterface $dataSet): void
    {
    }

    public function getCurrentDataSet(): ?DataSetInterface
    {
        return null;
    }

    public static function staticGetLastException(): ?\Throwable
    {
        return null;
    }

    public static function getBasilTestConfiguration(): ?ConfigurationInterface
    {
        return null;
    }

    public static function setClientManager(ClientManager $clientManager): void
    {
    }
}
