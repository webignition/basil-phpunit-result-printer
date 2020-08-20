<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

use webignition\BasilPhpUnitResultPrinter\Model\Statement\StatementInterface;

class Step implements DocumentSourceInterface
{
    private const TYPE = 'step';

    private string $name;
    private string $status;

    /**
     * @var StatementInterface[]
     */
    private array $statements;

    /**
     * @var array<mixed>|null
     */
    private ?array $data;

    /**
     * @param string $name
     * @param string $status
     * @param StatementInterface[] $statements
     * @param array<mixed> $data
     */
    public function __construct(string $name, string $status, array $statements, array $data = null)
    {
        $this->name = $name;
        $this->status = $status;
        $this->statements = array_filter($statements, function ($item) {
            return $item instanceof StatementInterface;
        });
        $this->data = $data;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getData(): array
    {
        $data = [
            'name' => $this->name,
            'status' => $this->status,
        ];

        if (count($this->statements)) {
            $statementData = [];

            foreach ($this->statements as $statement) {
                $statementData[] = $statement->getData();
            }

            $data['statements'] = $statementData;
        }

        if (null !== $this->data) {
            $data['data'] = $this->data;
        }

        return $data;
    }
}
