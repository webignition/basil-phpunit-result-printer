<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Event\Code\TestMethod;
use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;
use webignition\BasilModels\Model\StatementFactory;
use webignition\BasilModels\Model\StatementInterface;
use webignition\BasilModels\Model\UnknownEncapsulatedStatementException;

readonly class TestMetaDataExtractor
{
    public function __construct(
        private StatementFactory $statementFactory,
    ) {}

    public function extract(TestMethod $testMethod): TestMetaData
    {
        $reflectionClass = new \ReflectionClass($testMethod->className());
        $reflectionMethod = $reflectionClass->getMethod($testMethod->methodName());

        $stepNameAttributes = $reflectionMethod->getAttributes(StepName::class);
        $stepNameAttribute = $stepNameAttributes[0];

        $statementsAttributes = $reflectionMethod->getAttributes(Statements::class);
        $statementsAttribute = $statementsAttributes[0];

        $statementsAsStrings = $statementsAttribute->newInstance()->statements;
        $statements = [];

        foreach ($statementsAsStrings as $statementAsString) {
            try {
                $statement = $this->statementFactory->createFromJson($statementAsString);
                if ($statement instanceof StatementInterface) {
                    $statements[] = $statement;
                }
            } catch (UnknownEncapsulatedStatementException) {
                // Intentionally ignore UnknownEncapsulatedStatementException
            }
        }

        return new TestMetaData(
            $stepNameAttribute->newInstance()->name,
            new StatementCollection($statements),
        );
    }
}
