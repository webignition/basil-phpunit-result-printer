<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Exception;

use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;

class InvalidLocator extends AbstractException
{
    private const TYPE = 'invalid-locator';

    private string $type;
    private string $locator;
    private NodeSource $source;

    public function __construct(string $type, string $locator, NodeSource $source)
    {
        $this->type = $type;
        $this->locator = $locator;
        $this->source = $source;
    }

    protected function getType(): string
    {
        return self::TYPE;
    }

    protected function getBody(): array
    {
        return [
            'type' => $this->type,
            'locator' => $this->locator,
            'source' => $this->source->getData(),
        ];
    }
}
