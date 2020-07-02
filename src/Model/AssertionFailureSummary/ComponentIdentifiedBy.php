<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\Comment;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableInterface;
use webignition\DomElementIdentifier\AttributeIdentifierInterface;
use webignition\DomElementIdentifier\ElementIdentifierInterface;

class ComponentIdentifiedBy implements RenderableInterface
{
    private string $type;
    private Comment $identifier;

    public function __construct(ElementIdentifierInterface $identifier)
    {
        $this->type = $identifier instanceof AttributeIdentifierInterface ? 'attribute' : 'element';
        $this->identifier = new Comment((string) $identifier);
    }

    public function render(): string
    {
        return sprintf(
            '%s %s identified by:',
            $this->type,
            $this->identifier->render()
        );
    }
}
