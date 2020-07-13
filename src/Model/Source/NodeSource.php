<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Source;

use webignition\BasilPhpUnitResultPrinter\Model\Node;

class NodeSource extends AbstractSource
{
    private const TYPE = 'node';

    private Node $body;

    public function __construct(Node $node)
    {
        $this->body = $node;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getBody(): Node
    {
        return $this->body;
    }
}
