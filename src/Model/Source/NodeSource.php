<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Source;

use webignition\BasilPhpUnitResultPrinter\Model\Node;

class NodeSource extends AbstractSource
{
    private const TYPE = 'node';

    public function __construct(
        private Node $body
    ) {
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
