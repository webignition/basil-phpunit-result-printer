<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\StatementLine;

use webignition\BasilModels\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\HighlightedFailure;
use webignition\BasilPhpUnitResultPrinter\Model\Literal;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\Model\StatusIcon;

class Header implements RenderableInterface
{
    private StatusIcon $statusIcon;
    private RenderableInterface $source;

    public function __construct(StatementInterface $statement, int $status)
    {
        $this->statusIcon = new StatusIcon($status);
        $this->source = Status::SUCCESS === $status
            ? new Literal($statement->getSource())
            : new HighlightedFailure($statement->getSource());
    }

    public function render(): string
    {
        return sprintf(
            '%s %s',
            $this->statusIcon->render(),
            $this->source->render()
        );
    }
}
