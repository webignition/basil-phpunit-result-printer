<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model;

use webignition\BasilPhpUnitResultPrinter\Model\Scalar;
use webignition\BasilValueTypeIdentifier\ValueTypeIdentifier;

class ScalarFactory
{
    private ValueTypeIdentifier $valueTypeIdentifier;

    public function __construct(ValueTypeIdentifier $valueTypeIdentifier)
    {
        $this->valueTypeIdentifier = $valueTypeIdentifier;
    }

    public static function createFactory(): self
    {
        return new ScalarFactory(
            new ValueTypeIdentifier()
        );
    }

    public function create(string $source): ?Scalar
    {
        $type = $this->getType($source);

        if (null !== $type) {
            return new Scalar($type, $source);
        }

        return null;
    }

    private function getType(string $source): ?string
    {
        if ($this->valueTypeIdentifier->isBrowserProperty($source)) {
            return Scalar::TYPE_BROWSER_PROPERTY;
        }

        if ($this->valueTypeIdentifier->isDataParameter($source)) {
            return Scalar::TYPE_DATA_PARAMETER;
        }

        if ($this->valueTypeIdentifier->isEnvironmentValue($source)) {
            return Scalar::TYPE_ENVIRONMENT_PARAMETER;
        }

        if ($this->valueTypeIdentifier->isLiteralValue($source)) {
            return Scalar::TYPE_LITERAL;
        }

        if ($this->valueTypeIdentifier->isPageProperty($source)) {
            return Scalar::TYPE_PAGE_PROPERTY;
        }

        return null;
    }
}
