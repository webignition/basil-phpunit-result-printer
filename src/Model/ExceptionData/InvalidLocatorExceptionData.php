<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\ExceptionData;

use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;

class InvalidLocatorExceptionData extends AbstractExceptionData
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
