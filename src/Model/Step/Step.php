<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Step;

use webignition\BaseBasilTestCase\BasilTestCaseInterface;
use webignition\BasilModels\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\DataSet\DataSetInterface;
use webignition\BasilPhpUnitResultPrinter\Model\DataSet\KeyValueCollection;
use webignition\BasilPhpUnitResultPrinter\Model\IndentedContent;
use webignition\BasilPhpUnitResultPrinter\Model\Literal;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableCollection;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableInterface;
use webignition\BasilPhpUnitResultPrinter\Model\StatementLine\StatementLine;
use webignition\BasilPhpUnitResultPrinter\Model\Status;

class Step implements RenderableInterface
{
    private Name $name;
    private ?RenderableInterface $dataSet;
    private ?RenderableInterface $completedStatements;
    private ?RenderableInterface $failedStatement = null;
    private ?RenderableInterface $lastException = null;

    public function __construct(BasilTestCaseInterface $test)
    {
        $this->name = new Name($test);
        $this->dataSet = $this->createDataSet($test);
        $this->completedStatements = $this->createCompletedStatements($test);
    }

    public function setFailedStatement(RenderableInterface $failedStatement): void
    {
        $this->failedStatement = $failedStatement;
    }

    public function setLastException(RenderableInterface $lastException): void
    {
        $this->lastException = $lastException;
    }

    public function render(): string
    {
        $dataSet = null === $this->dataSet
            ? null
            : new RenderableCollection([
                new IndentedContent($this->dataSet, 2),
                new Literal(''),
            ]);

        $failedStatement = null === $this->failedStatement
            ? null
            : new IndentedContent($this->failedStatement);

        $lastException = null === $this->lastException
            ? null
            : new IndentedContent($this->lastException);

        $items = [
            $this->name,
            $dataSet,
            $this->completedStatements,
            $failedStatement,
            $lastException,
        ];

        return (new RenderableCollection($items))->render();
    }

    private function createDataSet(BasilTestCaseInterface $test): ?RenderableInterface
    {
        $dataSet = $test->getCurrentDataSet();
        if ($dataSet instanceof DataSetInterface) {
            return new RenderableCollection([
                KeyValueCollection::fromDataSet($dataSet),
            ]);
        }

        return null;
    }

    private function createCompletedStatements(BasilTestCaseInterface $test): ?RenderableInterface
    {
        $completedStatements = $test->getHandledStatements();
        if (Status::FAILURE === $test->getStatus()) {
            array_pop($completedStatements);
        }

        if (0 === count($completedStatements)) {
            return null;
        }

        $renderableStatements = [];
        foreach ($completedStatements as $completedStatement) {
            if (false === $completedStatement instanceof DerivedValueOperationAssertion) {
                $renderableStatements[] = new StatementLine($completedStatement, Status::SUCCESS);
            }
        }

        return new IndentedContent(new RenderableCollection($renderableStatements));
    }
}
