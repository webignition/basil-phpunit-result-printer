<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\StepDataExtractor;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Framework\Attributes\DataProvider;

readonly class DataSetExtractor
{
    /**
     * @return array<mixed>
     */
    public function extract(TestMethod $testMethod): array
    {
        $reflectionClass = new \ReflectionClass($testMethod->className());
        $reflectionMethod = $reflectionClass->getMethod($testMethod->methodName());

        $dataProviderAttributes = $reflectionMethod->getAttributes(DataProvider::class);
        $dataProviderAttribute = $dataProviderAttributes[0];

        $dataProviderMethodName = $dataProviderAttribute->newInstance()->methodName();

        $className = $reflectionClass->getName();
        if (!class_exists($className)) {
            return [];
        }

        if (!method_exists($className, $dataProviderMethodName)) {
            return [];
        }

        $testData = $className::$dataProviderMethodName();
        if (!is_array($testData)) {
            return [];
        }

        $testMethodTestData = $testMethod->testData();

        if (!$testMethodTestData->hasDataFromDataProvider()) {
            return [];
        }

        $dataSetName = $testMethodTestData->dataFromDataProvider()->dataSetName();

        $dataSet = $testData[$dataSetName] ?? [];

        return is_array($dataSet) ? $dataSet : [];
    }
}
