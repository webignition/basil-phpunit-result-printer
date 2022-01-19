<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model\Source;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\IdentifierFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\Model\Node;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;

class NodeSourceFactory
{
    public function __construct(
        private IdentifierFactory $identifierFactory
    ) {
    }

    public static function createFactory(): self
    {
        return new NodeSourceFactory(
            IdentifierFactory::createFactory()
        );
    }

    public function create(string $source): ?NodeSource
    {
        $identifier = $this->identifierFactory->create($source);
        if ($identifier instanceof Identifier) {
            return new NodeSource(Node::fromIdentifier($identifier));
        }

        return null;
    }
}
