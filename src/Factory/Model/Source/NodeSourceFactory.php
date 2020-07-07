<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model\Source;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\IdentifierFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;

class NodeSourceFactory
{
    private IdentifierFactory $identifierFactory;

    public function __construct(IdentifierFactory $identifierFactory)
    {
        $this->identifierFactory = $identifierFactory;
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
