<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\ExceptionData;

use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;

class InvalidLocatorExceptionData extends AbstractExceptionData
{
    private const TYPE = 'invalid-locator';

    public function __construct(
        private string $type,
        private string $locator,
        private NodeSource $source
    ) {
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
