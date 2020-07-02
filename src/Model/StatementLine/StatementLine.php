<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\StatementLine;

use webignition\BasilModels\Action\ResolvedAction;
use webignition\BasilModels\Assertion\ResolvedAssertion;
use webignition\BasilModels\EncapsulatingStatementInterface;
use webignition\BasilModels\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\IndentedContent;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableCollection;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;

class StatementLine implements RenderableInterface
{
    private RenderableCollection $renderableCollection;

    public function __construct(StatementInterface $statement, int $status)
    {
        $this->renderableCollection = new RenderableCollection([
            new Header($statement, $status),
        ]);

        if ($statement instanceof EncapsulatingStatementInterface) {
            if (Status::SUCCESS === $status) {
                $this->renderableCollection = $this->renderableCollection->append(
                    new IndentedContent($this->createEncapsulatedSource($statement))
                );
            }

            if (Status::FAILURE === $status) {
                $this->renderableCollection = $this->renderableCollection->append(
                    new IndentedContent($this->createEncapsulatedSourceRecursive($statement))
                );
            }
        }
    }

    public function withFailureSummary(RenderableInterface $failureSummary): self
    {
        $new = clone $this;
        $new->renderableCollection = $new->renderableCollection->append($failureSummary);

        return $new;
    }

    public function render(): string
    {
        return $this->renderableCollection->render();
    }

    private function createEncapsulatedSource(EncapsulatingStatementInterface $statement): RenderableInterface
    {
        $label = $statement instanceof ResolvedAction || $statement instanceof ResolvedAssertion
            ? 'resolved from'
            : 'derived from';

        return new LabelledStatement($label, $statement->getSourceStatement());
    }

    private function createEncapsulatedSourceRecursive(EncapsulatingStatementInterface $statement): RenderableInterface
    {
        $renderableContent = new RenderableCollection([
            $this->createEncapsulatedSource($statement)
        ]);

        $sourceStatement = $statement->getSourceStatement();
        if ($sourceStatement instanceof ResolvedAction || $sourceStatement instanceof ResolvedAssertion) {
            $renderableContent = $renderableContent->append(
                $this->createEncapsulatedSourceRecursive($sourceStatement)
            );
        }

        return $renderableContent;
    }
}
