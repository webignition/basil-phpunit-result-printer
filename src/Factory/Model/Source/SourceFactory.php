<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model\Source;

use webignition\BasilIdentifierAnalyser\IdentifierTypeAnalyser;
use webignition\BasilPhpUnitResultPrinter\Model\Source\SourceInterface;

class SourceFactory
{
    private NodeSourceFactory $nodeSourceFactory;
    private ScalarSourceFactory $scalarSourceFactory;
    private IdentifierTypeAnalyser $identifierTypeAnalyser;

    public function __construct(
        NodeSourceFactory $nodeSourceFactory,
        ScalarSourceFactory $scalarSourceFactory,
        IdentifierTypeAnalyser $identifierTypeAnalyser
    ) {
        $this->nodeSourceFactory = $nodeSourceFactory;
        $this->scalarSourceFactory = $scalarSourceFactory;
        $this->identifierTypeAnalyser = $identifierTypeAnalyser;
    }

    public static function createFactory(): self
    {
        return new SourceFactory(
            NodeSourceFactory::createFactory(),
            ScalarSourceFactory::createFactory(),
            IdentifierTypeAnalyser::create()
        );
    }

    public function create(string $source): ?SourceInterface
    {
        if ($this->identifierTypeAnalyser->isDomOrDescendantDomIdentifier($source)) {
            return $this->nodeSourceFactory->create($source);
        }

        return $this->scalarSourceFactory->create($source);
    }
}
