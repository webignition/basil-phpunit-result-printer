<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier;

use webignition\BasilDomIdentifierFactory\Factory as DomIdentifierFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Properties;
use webignition\DomElementIdentifier\AttributeIdentifierInterface;
use webignition\DomElementIdentifier\ElementIdentifierInterface;

class PropertiesFactory
{
    public function __construct(
        private DomIdentifierFactory $domIdentifierFactory
    ) {}

    public static function createFactory(): self
    {
        return new PropertiesFactory(
            DomIdentifierFactory::createFactory()
        );
    }

    public function create(string $source): ?Properties
    {
        $domIdentifier = $this->domIdentifierFactory->createFromIdentifierString($source);

        if ($domIdentifier instanceof ElementIdentifierInterface) {
            $type = $domIdentifier->isCssSelector()
                ? Properties::TYPE_CSS
                : Properties::TYPE_XPATH;

            $locator = $domIdentifier->getLocator();
            $position = $domIdentifier->getOrdinalPosition() ?? 1;

            $properties = new Properties($type, $locator, $position);

            $parent = $domIdentifier->getParentIdentifier();
            if ($parent instanceof ElementIdentifierInterface) {
                $parentProperties = $this->create((string) $parent);

                if ($parentProperties instanceof Properties) {
                    $properties = $properties->withParent($parentProperties);
                }
            }

            if ($domIdentifier instanceof AttributeIdentifierInterface) {
                $properties = $properties->withAttribute($domIdentifier->getAttributeName());
            }

            return $properties;
        }

        return null;
    }
}
