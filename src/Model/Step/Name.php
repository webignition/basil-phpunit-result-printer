<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Step;

use webignition\BaseBasilTestCase\BasilTestCaseInterface;
use webignition\BasilModels\DataSet\DataSetInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Failure;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\Model\StatusIcon;
use webignition\BasilPhpUnitResultPrinter\Model\Success;

class Name implements RenderableInterface
{
    private StatusIcon $statusIcon;
    private RenderableInterface $nameLine;

    public function __construct(BasilTestCaseInterface $test)
    {
        $status = $test->getStatus();
        $name = $test->getBasilStepName();
        $dataSet = $test->getCurrentDataSet();
        if ($dataSet instanceof DataSetInterface) {
            $name .= ': ' . $dataSet->getName();
        }

        $this->statusIcon = new StatusIcon($status);
        $this->nameLine = Status::SUCCESS === $status
            ? new Success($name)
            : new Failure($name);
    }

    public function render(): string
    {
        return sprintf(
            '%s %s',
            $this->statusIcon->render(),
            $this->nameLine->render()
        );
    }
}
