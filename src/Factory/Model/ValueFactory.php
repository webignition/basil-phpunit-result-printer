<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\SourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Source\SourceInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Value;

class ValueFactory
{
    public function __construct(
        private SourceFactory $sourceFactory
    ) {
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
