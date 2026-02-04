<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\TestDataExtractor;

use PHPUnit\Event\Code\TestMethod;
use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BasilModels\Model\Statement\InvalidStatementDataException;
use webignition\BasilModels\Model\Statement\StatementFactory;
use webignition\BasilModels\Model\Statement\UnknownEncapsulatedStatementException;
use webignition\BasilPhpUnitResultPrinter\StatementCollection;

readonly class StepStatementCollectionExtractor
{
    public function __construct(
        private StatementFactory $statementFactory,
    ) {}

    public function extract(TestMethod $testMethod): StatementCollection
    {
        $reflectionClass = new \ReflectionClass($testMethod->className());
        $reflectionMethod = $reflectionClass->getMethod($testMethod->methodName());

        $statementsAttributes = $reflectionMethod->getAttributes(Statements::class);
        $statementsAttribute = $statementsAttributes[0];

        $statementsAsStrings = $statementsAttribute->newInstance()->statements;
        $statements = [];

        foreach ($statementsAsStrings as $statementAsString) {
            try {
                $statements[] = $this->statementFactory->createFromJson($statementAsString);
            } catch (UnknownEncapsulatedStatementException) {
                // Intentionally ignore UnknownEncapsulatedStatementException
            } catch (InvalidStatementDataException) {
                // Intentionally ignore InvalidStatementDataException
            }
        }

        return new StatementCollection($statements);
    }
}
