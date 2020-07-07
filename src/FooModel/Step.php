<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel;

use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\StatementInterface;

class Step implements DocumentSourceInterface
{
    private string $name;
    private string $status;

    /**
     * @var StatementInterface[]
     */
    private array $statements;

    /**
     * @param string $name
     * @param string $status
     * @param StatementInterface[] $statements
     */
    public function __construct(string $name, string $status, array $statements)
    {
        $this->name = $name;
        $this->status = $status;
        $this->statements = array_filter($statements, function ($item) {
            return $item instanceof StatementInterface;
        });
    }

    public function getData(): array
    {
        $statementData = [];

        foreach ($this->statements as $statement) {
            $statementData[] = $statement->getData();
        }

        return [
            'name' => $this->name,
            'status' => $this->status,
            'statements' => $statementData,
        ];
    }
}
