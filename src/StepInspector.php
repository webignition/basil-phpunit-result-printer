<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;
use webignition\BasilModels\Model\Statement\InvalidStatementDataException;
use webignition\BasilModels\Model\Statement\StatementFactory;
use webignition\BasilModels\Model\Statement\UnknownEncapsulatedStatementException;

class StepInspector
{
    private TestMethod $testMethod;

    /**
     * @var \ReflectionClass<object>
     */
    private \ReflectionClass $reflectionClass;
    private \ReflectionMethod $reflectionMethod;

    private StatementFactory $statementFactory;

    public function __construct(
        StatementFactory $statementFactory,
    ) {
        $this->statementFactory = $statementFactory;
    }

    public function setTestMethod(TestMethod $testMethod): void
    {
        $this->testMethod = $testMethod;
        $this->reflectionClass = new \ReflectionClass($testMethod->className());
        $this->reflectionMethod = $this->reflectionClass->getMethod($testMethod->methodName());
    }

    /**
     * @return null|array<mixed>
     */
    public function getDataSet(): ?array
    {
        $dataProviderAttributes = $this->reflectionMethod->getAttributes(DataProvider::class);
        if ([] === $dataProviderAttributes) {
            return null;
        }

        $dataProviderAttribute = $dataProviderAttributes[0];
        $dataProviderMethodName = $dataProviderAttribute->newInstance()->methodName();

        $className = $this->reflectionClass->getName();
        if (!class_exists($className)) {
            return null;
        }

        if (!method_exists($className, $dataProviderMethodName)) {
            return null;
        }

        $testData = $className::$dataProviderMethodName();
        if (!is_array($testData)) {
            return null;
        }

        $testMethodTestData = $this->testMethod->testData();
        if (!$testMethodTestData->hasDataFromDataProvider()) {
            return null;
        }

        $dataSetName = $testMethodTestData->dataFromDataProvider()->dataSetName();

        $dataSet = $testData[$dataSetName] ?? null;

        return is_array($dataSet) ? $dataSet : null;
    }

    public function getName(): string
    {
        $stepNameAttributes = $this->reflectionMethod->getAttributes(StepName::class);
        $stepNameAttribute = $stepNameAttributes[0];

        return $stepNameAttribute->newInstance()->name;
    }

    public function getStatements(): StatementCollection
    {
        $statementsAttributes = $this->reflectionMethod->getAttributes(Statements::class);
        $statementsAttribute = $statementsAttributes[0];

        $statementsAsStrings = $statementsAttribute->newInstance()->statements;
        $statements = [];

        foreach ($statementsAsStrings as $statementAsString) {
            try {
                $statements[] = $this->statementFactory->createFromJson($statementAsString);
            } catch (InvalidStatementDataException|UnknownEncapsulatedStatementException) {
                // Intentionally ignore UnknownEncapsulatedStatementException
                // Intentionally ignore InvalidStatementDataException
            }
        }

        return new StatementCollection($statements);
    }
}
