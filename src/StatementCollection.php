<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilModels\Model\Statement\StatementInterface;

/**
 * @implements \IteratorAggregate<StatementInterface>
 */
class StatementCollection implements \IteratorAggregate
{
    /**
     * @param StatementInterface[] $statements
     */
    public function __construct(
        private readonly array $statements,
    ) {}

    /**
     * @return StatementInterface[]
     */
    public function getHandledStatements(?StatementInterface $failedStatement): array
    {
        if (null === $failedStatement) {
            return $this->statements;
        }

        $handledStatements = [];
        $hasFoundFailedStatement = false;

        foreach ($this->statements as $statement) {
            if ($statement->getIndex() === $failedStatement->getIndex()) {
                $hasFoundFailedStatement = true;
            }

            if (false === $hasFoundFailedStatement) {
                $handledStatements[] = $statement;
            }
        }

        return $handledStatements;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->statements);
    }
}
