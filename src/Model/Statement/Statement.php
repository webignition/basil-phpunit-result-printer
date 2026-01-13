<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Statement;

use webignition\BasilPhpUnitResultPrinter\Enum\StatementType;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\ExceptionDataInterface;

class Statement implements StatementInterface
{
    /**
     * @var Transformation[]
     */
    private array $transformations = [];

    private ?ExceptionDataInterface $exceptionData = null;

    private ?AssertionFailureSummaryInterface $failureSummary = null;

    public function __construct(
        private StatementType $type,
        private string $source,
        private string $status,
    ) {}

    public function withExceptionData(ExceptionDataInterface $exceptionData): self
    {
        $new = clone $this;
        $new->exceptionData = $exceptionData;

        return $new;
    }

    public function withFailureSummary(AssertionFailureSummaryInterface $summary): self
    {
        $new = clone $this;
        $new->failureSummary = $summary;

        return $new;
    }

    public function withTransformations(array $transformations): StatementInterface
    {
        $new = clone $this;
        $new->transformations = $transformations;

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

        if ($this->failureSummary instanceof AssertionFailureSummaryInterface) {
            $data['summary'] = $this->failureSummary->getData();
        }

        return $data;
    }
}
