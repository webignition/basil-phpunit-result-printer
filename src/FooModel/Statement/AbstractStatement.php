<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Statement;

abstract class AbstractStatement implements StatementInterface
{
    private string $type;
    private string $source;
    private string $status;

    /**
     * @var Transformation[]
     */
    private array $transformations = [];

    /**
     * @param string $type
     * @param string $source
     * @param string $status
     * @param array<mixed> $transformations
     */
    public function __construct(string $type, string $source, string $status, array $transformations = [])
    {
        $this->type = $type;
        $this->source = $source;
        $this->status = $status;
        $this->transformations = array_filter($transformations, function ($item) {
            return $item instanceof Transformation;
        });
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        $data = [
            'type' => $this->type,
            'source' => $this->source,
            'status' => $this->status,
        ];

        if (0 !== count($this->transformations)) {
            $transformationsData = [];

            foreach ($this->transformations as $transformation) {
                $transformationsData[] = $transformation->getData();
            }

            $data['transformations'] = $transformationsData;
        }

        return $data;
    }
}
