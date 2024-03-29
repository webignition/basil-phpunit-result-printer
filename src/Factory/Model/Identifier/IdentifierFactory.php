<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier;

use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Properties;

class IdentifierFactory
{
    public function __construct(
        private PropertiesFactory $propertiesFactory
    ) {
    }

    public static function createFactory(): self
    {
        return new IdentifierFactory(
            PropertiesFactory::createFactory()
        );
    }

    public function create(string $source): ?Identifier
    {
        $properties = $this->propertiesFactory->create($source);

        if ($properties instanceof Properties) {
            return new Identifier($source, $properties);
        }

        return null;
    }
}
