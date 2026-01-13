<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Statement;

use webignition\BasilPhpUnitResultPrinter\Enum\StatementType;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\ExceptionDataInterface;

class Statement implements StatementInterface
{
    /**
     * @var Transformation[]
     */
    private array $transformations;

    private ?ExceptionDataInterface $exceptionData = null;

    /**
     * @param array<mixed> $transformations
     */
    public function __construct(
        private StatementType $type,
        private string $source,
        private string $status,
        array $transformations = []
    ) {
        $this->transformations = array_filter($transformations, function ($item) {
            return $item instanceof Transformation;
        });
    }

    public function withExceptionData(ExceptionDataInterface $exceptionData): self
    {
        $new = clone $this;
        $new->exceptionData = $exceptionData;

        return $new;
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        $data = [
            'type' => $this->type->value,
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

        if (null !== $this->exceptionData) {
            $data['exception'] = $this->exceptionData->getData();
        }

        return $data;
    }
}
