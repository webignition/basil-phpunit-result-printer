<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\IndentedContent;
use webignition\BasilPhpUnitResultPrinter\Model\Literal;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableCollection;
use webignition\DomElementIdentifier\ElementIdentifierInterface;

class ExistenceSummary extends RenderableCollection
{
    public function __construct(ElementIdentifierInterface $identifier, string $operator)
    {
        $ancestorHierarchy = null === $identifier->getParentIdentifier()
            ? null
            : new IndentedContent(new AncestorHierarchy($identifier));

        parent::__construct([
            new ComponentIdentifiedBy($identifier),
            new IndentedContent(new IdentifierProperties($identifier), 2),
            $ancestorHierarchy,
            new IndentedContent(new Literal('exists' === $operator ? 'does not exist' : 'does exist'))
        ]);
    }

    public function render(): string
    {
        $content = parent::render();

        $content = ucfirst($content);
        return '* ' . $content;
    }
}
