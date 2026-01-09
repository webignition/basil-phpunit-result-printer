<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilModels\Model\StatementInterface;

class StatementCollection
{
    private ?StatementInterface $failedStatement = null;

    /**
     * @param StatementInterface[] $statements
     */
    public function __construct(
        private readonly array $statements,
    ) {}

    public function setFailedStatement(StatementInterface $statement): void
    {
        $this->failedStatement = $statement;
    }

    /**
     * @return StatementInterface[]
     */
    public function getHandledStatements(): array
    {
        if (null === $this->failedStatement) {
            return $this->statements;
        }

        $handledStatements = [];
        $hasFoundFailedStatement = false;

        foreach ($this->statements as $statement) {
            if ($statement->getIndex() === $this->failedStatement->getIndex()) {
                $hasFoundFailedStatement = true;
            }

            if (false === $hasFoundFailedStatement) {
                $handledStatements[] = $statement;
            }
        }

        return $handledStatements;
    }
}
