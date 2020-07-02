<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\IndentedContent;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableCollection;
use webignition\DomElementIdentifier\ElementIdentifierInterface;

class AncestorHierarchy extends RenderableCollection
{
    public function __construct(ElementIdentifierInterface $identifier)
    {
        $items = [];

        $parent = $identifier->getParentIdentifier();

        while ($parent instanceof ElementIdentifierInterface) {
            $items[] = new WithParent();
            $items[] = new IndentedContent(new IdentifierProperties($parent));

            $parent = $parent->getParentIdentifier();
        }

        parent::__construct($items);
    }
}
