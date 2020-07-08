<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\SourceFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\SourceInterface;
use webignition\BasilPhpUnitResultPrinter\FooModel\Value;

class ValueFactory
{
    private SourceFactory $sourceFactory;

    public function __construct(SourceFactory $sourceFactory)
    {
        $this->sourceFactory = $sourceFactory;
    }

    public static function createFactory(): self
    {
        return new ValueFactory(
            SourceFactory::createFactory()
        );
    }

    public function create(string $value, string $sourceString): ?Value
    {
        $source = $this->sourceFactory->create($sourceString);

        if ($source instanceof SourceInterface) {
            return new Value($value, $source);
        }

        return null;
    }
}
